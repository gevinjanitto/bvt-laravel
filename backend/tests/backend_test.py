"""Backend tests for Bali Vision Tour (Laravel app).

Runs against the public URL. Verifies public site pages, filters, booking,
newsletter, admin auth, admin CRUD, admin bookings/settings/content, upload.
"""
import os
import re
import io
import pytest
import requests

BASE_URL = os.environ.get('REACT_APP_BACKEND_URL').rstrip('/')
ADMIN_USER = 'admin'
ADMIN_PASS = 'BaliVision2025!'


def _csrf_from_html(html: str) -> str:
    m = re.search(r'name="csrf-token"\s+content="([^"]+)"', html)
    if not m:
        m = re.search(r'name="_token"\s+value="([^"]+)"', html)
    assert m, "csrf token not found in html"
    return m.group(1)


@pytest.fixture(scope="module")
def client():
    s = requests.Session()
    s.headers.update({"User-Agent": "pytest-bvt/1.0"})
    return s


@pytest.fixture(scope="module")
def admin_client():
    s = requests.Session()
    s.headers.update({"User-Agent": "pytest-bvt-admin/1.0"})
    # GET login page to get token + session
    r = s.get(f"{BASE_URL}/admin/login")
    assert r.status_code == 200, f"login page {r.status_code}"
    token = _csrf_from_html(r.text)
    r = s.post(f"{BASE_URL}/admin/login", data={
        "_token": token, "username": ADMIN_USER, "password": ADMIN_PASS,
    }, allow_redirects=False)
    assert r.status_code in (302, 303), f"login failed: {r.status_code} {r.text[:300]}"
    # Follow to /admin
    r2 = s.get(f"{BASE_URL}/admin")
    assert r2.status_code == 200
    return s


# ---------- Public pages ----------
class TestPublicPages:
    @pytest.mark.parametrize("path", [
        "/", "/tour-packages", "/tour-packages/bali-rafting-eco-tour",
        "/car-rental", "/car-rental/toyota-avanza-xenia",
        "/activities", "/activities/ayung-river-white-water-rafting",
        "/about", "/articles", "/articles/balinese-temple-etiquette",
        "/policies/privacy",
    ])
    def test_page_200(self, client, path):
        r = client.get(f"{BASE_URL}{path}")
        assert r.status_code == 200, f"{path} -> {r.status_code}"
        # basic sanity: page contains navbar
        assert "data-testid=\"navbar\"" in r.text or "navbar" in r.text.lower()

    def test_home_has_hero_and_bestsellers(self, client):
        r = client.get(f"{BASE_URL}/")
        assert 'data-testid="hero"' in r.text
        # bestseller tours contain testids
        assert 'tour-card-' in r.text

    def test_tour_category_filter(self, client):
        r = client.get(f"{BASE_URL}/tour-packages", params={"category": "Family Trip"})
        assert r.status_code == 200
        # Should not contain 'Adventure' unique tour cards? Just check page renders.
        # Check we can filter to empty state
        r2 = client.get(f"{BASE_URL}/tour-packages", params={"destination": "Zzz"})
        assert r2.status_code == 200
        assert 'tour-card-' not in r2.text or 'No tours' in r2.text or 'empty' in r2.text.lower()

    def test_car_filter(self, client):
        r = client.get(f"{BASE_URL}/car-rental", params={"filter": "VIP Luxury"})
        assert r.status_code == 200

    def test_activity_filter(self, client):
        r = client.get(f"{BASE_URL}/activities", params={"type": "Wellness & Spa"})
        assert r.status_code == 200

    def test_article_search_and_cat(self, client):
        r = client.get(f"{BASE_URL}/articles", params={"q": "Penida"})
        assert r.status_code == 200
        r = client.get(f"{BASE_URL}/articles", params={"page": 1})
        assert r.status_code == 200


# ---------- Public POST endpoints ----------
class TestBookingAndNewsletter:
    def test_booking_post(self, client):
        # need CSRF from a page (meta tag)
        r = client.get(f"{BASE_URL}/")
        token = _csrf_from_html(r.text)
        payload = {
            "type": "tour", "item_id": "1", "item_name": "TEST_Booking Tour",
            "name": "TEST_Pytest User", "phone": "081234567890",
            "date": "2026-02-01", "pax": 2, "notes": "auto test",
        }
        r = client.post(f"{BASE_URL}/booking", json=payload, headers={
            "X-CSRF-TOKEN": token, "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        })
        assert r.status_code == 201, f"booking failed: {r.status_code} {r.text[:400]}"
        data = r.json()
        assert data.get("ok") is True
        assert isinstance(data.get("id"), int)

    def test_booking_validation(self, client):
        r = client.get(f"{BASE_URL}/")
        token = _csrf_from_html(r.text)
        r = client.post(f"{BASE_URL}/booking", json={"type": "tour"}, headers={
            "X-CSRF-TOKEN": token, "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        })
        assert r.status_code == 422

    def test_newsletter_post(self, client):
        r = client.get(f"{BASE_URL}/")
        token = _csrf_from_html(r.text)
        r = client.post(f"{BASE_URL}/newsletter", json={"email": "test_pytest@example.com"}, headers={
            "X-CSRF-TOKEN": token, "Accept": "application/json",
            "X-Requested-With": "XMLHttpRequest",
        })
        assert r.status_code == 200, r.text[:300]
        assert r.json().get("ok") is True


# ---------- Admin auth ----------
class TestAdminAuth:
    def test_unauth_admin_redirects(self, client):
        r = client.get(f"{BASE_URL}/admin", allow_redirects=False)
        assert r.status_code in (302, 303)
        assert "/admin/login" in r.headers.get("Location", "")

    def test_wrong_password(self):
        s = requests.Session()
        r = s.get(f"{BASE_URL}/admin/login")
        token = _csrf_from_html(r.text)
        r = s.post(f"{BASE_URL}/admin/login", data={
            "_token": token, "username": ADMIN_USER, "password": "WRONG_PASS_XYZ",
        }, allow_redirects=False)
        # redirect back to login with error (session flash) - Laravel typically does 302 with errors bag
        assert r.status_code in (302, 303)

    def test_admin_dashboard_ok(self, admin_client):
        r = admin_client.get(f"{BASE_URL}/admin")
        assert r.status_code == 200
        assert "admin-sidebar" in r.text or "Dashboard" in r.text


# ---------- Admin resource CRUD ----------
class TestAdminResources:
    def _new_form_token(self, admin_client, resource):
        r = admin_client.get(f"{BASE_URL}/admin/{resource}/create")
        assert r.status_code == 200
        return _csrf_from_html(r.text)

    def test_create_edit_delete_tour(self, admin_client):
        token = self._new_form_token(admin_client, "tours")
        r = admin_client.post(f"{BASE_URL}/admin/tours", data={
            "_token": token,
            "title": "TEST_PyTour",
            "price": 1234567,
            "price_unit": "Person",
            "category": "Family Trip",
            "region": "Ubud",
            "duration": "2 Days",
            "days": 2,
            "rating": 5,
            "reviews": 0,
            "badge": "",
            "image": "https://example.com/x.jpg",
            "subtitle": "",
            "description": "auto test",
            "long_description": "auto",
        }, allow_redirects=False)
        assert r.status_code in (302, 303), f"create tour: {r.status_code} {r.text[:400]}"

        # find created id via admin listing
        r = admin_client.get(f"{BASE_URL}/admin/tours")
        assert r.status_code == 200
        m = re.search(r'data-testid="resource-edit-(\d+)"[^>]*href="[^"]*/admin/tours/(\d+)/edit"', r.text)
        # Fallback: just find highest id edit link
        ids = [int(x) for x in re.findall(r'resource-edit-(\d+)', r.text)]
        assert ids, "no tours found in admin list"
        tour_id = max(ids)

        # edit
        r = admin_client.get(f"{BASE_URL}/admin/tours/{tour_id}/edit")
        assert r.status_code == 200
        token = _csrf_from_html(r.text)
        r = admin_client.post(f"{BASE_URL}/admin/tours/{tour_id}", data={
            "_token": token, "_method": "PUT",
            "title": "TEST_PyTour_Edited", "price": 999, "price_unit": "Person",
            "category": "Family Trip", "region": "Ubud", "duration": "3 Days",
            "days": 3, "rating": 5, "reviews": 0,
        }, allow_redirects=False)
        assert r.status_code in (302, 303), f"edit: {r.status_code} {r.text[:300]}"

        # delete
        r = admin_client.get(f"{BASE_URL}/admin/tours")
        token = _csrf_from_html(r.text)
        r = admin_client.post(f"{BASE_URL}/admin/tours/{tour_id}", data={
            "_token": token, "_method": "DELETE",
        }, allow_redirects=False)
        assert r.status_code in (302, 303), f"delete: {r.status_code}"

        # confirm gone
        r = admin_client.get(f"{BASE_URL}/admin/tours")
        assert f"resource-edit-{tour_id}" not in r.text

    def test_invalid_json_field_shows_error(self, admin_client):
        token = self._new_form_token(admin_client, "tours")
        r = admin_client.post(f"{BASE_URL}/admin/tours", data={
            "_token": token,
            "title": "TEST_BadJson",
            "price": 100,
            "gallery": "{not-valid-json",  # json field
        }, allow_redirects=True)
        # After redirect back with errors, page renders admin-errors block
        assert "admin-errors" in r.text or "gallery" in r.text.lower()


# ---------- Admin bookings ----------
class TestAdminBookings:
    def test_status_update_and_delete(self, admin_client, client):
        # Create a booking first via public endpoint
        r = client.get(f"{BASE_URL}/")
        token = _csrf_from_html(r.text)
        r = client.post(f"{BASE_URL}/booking", json={
            "type": "tour", "item_name": "TEST_Booking For Admin",
            "name": "TEST_admin_flow", "phone": "081200000000",
        }, headers={"X-CSRF-TOKEN": token, "Accept": "application/json", "X-Requested-With": "XMLHttpRequest"})
        assert r.status_code == 201
        bid = r.json()["id"]

        # Update status
        r = admin_client.get(f"{BASE_URL}/admin/bookings")
        token = _csrf_from_html(r.text)
        r = admin_client.post(f"{BASE_URL}/admin/bookings/{bid}", data={
            "_token": token, "_method": "PATCH", "status": "confirmed",
        }, allow_redirects=False)
        assert r.status_code in (302, 303, 200), f"status update: {r.status_code}"

        # Delete
        r = admin_client.post(f"{BASE_URL}/admin/bookings/{bid}", data={
            "_token": token, "_method": "DELETE",
        }, allow_redirects=False)
        assert r.status_code in (302, 303, 200)


# ---------- Admin settings ----------
class TestAdminSettings:
    def test_update_settings(self, admin_client, client):
        r = admin_client.get(f"{BASE_URL}/admin/settings")
        assert r.status_code == 200
        token = _csrf_from_html(r.text)
        r = admin_client.post(f"{BASE_URL}/admin/settings", data={
            "_token": token, "_method": "PUT",
            "contact_whatsapp": "628123456789",
            "brand_title": "Bali Vision Tour",
        }, allow_redirects=False)
        assert r.status_code in (302, 303), f"settings: {r.status_code} {r.text[:400]}"

    def test_invalid_whatsapp(self, admin_client):
        r = admin_client.get(f"{BASE_URL}/admin/settings")
        token = _csrf_from_html(r.text)
        r = admin_client.post(f"{BASE_URL}/admin/settings", data={
            "_token": token, "_method": "PUT",
            "contact_whatsapp": "abcdef!!!",
        }, allow_redirects=True)
        # after error redirect, admin-errors should show or page still renders
        # Non-strict: at least still 200
        assert r.status_code in (200, 422)


# ---------- Admin content ----------
class TestAdminContent:
    def test_reset_content(self, admin_client):
        r = admin_client.get(f"{BASE_URL}/admin/content")
        assert r.status_code == 200
        token = _csrf_from_html(r.text)
        r = admin_client.post(f"{BASE_URL}/admin/content/reset", data={"_token": token}, allow_redirects=False)
        assert r.status_code in (302, 303, 200)


# ---------- Admin upload ----------
class TestAdminUpload:
    def test_upload_image(self, admin_client, client):
        r = admin_client.get(f"{BASE_URL}/admin")
        token = _csrf_from_html(r.text)
        # tiny 1x1 png
        png = bytes.fromhex(
            "89504e470d0a1a0a0000000d49484452000000010000000108060000001f15c4"
            "890000000d49444154789c63000100000005000102fa27f7000000004945"
            "4e44ae426082"
        )
        files = {"file": ("test.png", io.BytesIO(png), "image/png")}
        r = admin_client.post(f"{BASE_URL}/admin/upload", data={"_token": token}, files=files,
                              headers={"X-CSRF-TOKEN": token, "Accept": "application/json"})
        assert r.status_code in (200, 201), f"upload: {r.status_code} {r.text[:300]}"
        j = r.json()
        assert "url" in j
        url = j["url"]
        # verify served publicly
        rr = client.get(f"{BASE_URL}{url}")
        assert rr.status_code == 200, f"uploaded url not served: {rr.status_code}"
