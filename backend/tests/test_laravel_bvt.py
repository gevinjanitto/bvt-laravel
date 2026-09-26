"""Backend tests for Laravel Bali Vision Tour app served on http://localhost:3000"""
import io
import re
import pytest
import requests

BASE = "http://localhost:3000"


def _csrf(session, path="/"):
    r = session.get(BASE + path)
    assert r.status_code == 200, f"GET {path} -> {r.status_code}"
    m = re.search(r'name="csrf-token"\s+content="([^"]+)"', r.text)
    if not m:
        m = re.search(r'name="_token"\s+value="([^"]+)"', r.text)
    assert m, f"No CSRF token found on {path}"
    return m.group(1)


# ---------- Public pages ----------
PUBLIC_PAGES = [
    "/", "/tour-packages", "/tour-packages/bali-rafting-eco-tour",
    "/car-rental", "/car-rental/toyota-alphard-vellfire-luxury",
    "/activities", "/activities/aloha-ubud-jungle-swing-photo-spot",
    "/about", "/articles", "/articles/balinese-temple-etiquette",
    "/policies/privacy", "/policies/terms", "/up",
]


@pytest.mark.parametrize("path", PUBLIC_PAGES)
def test_public_page_200(path):
    r = requests.get(BASE + path, timeout=15)
    assert r.status_code == 200, f"{path} -> {r.status_code}"
    # Detect Laravel/whoops error page
    assert "Whoops" not in r.text and "Illuminate\\" not in r.text, f"error page on {path}"


# ---------- Filters ----------
FILTER_URLS = [
    "/tour-packages?category=Private%20Tour",
    "/tour-packages?duration=1%20Day",
    "/car-rental?filter=Family%20MPV",
    "/activities?type=Adventure%20%26%20Trekking",
    "/articles?q=temple",
    "/articles?cat=Island%20Itineraries&page=1",
]


@pytest.mark.parametrize("url", FILTER_URLS)
def test_filters(url):
    r = requests.get(BASE + url, timeout=15)
    assert r.status_code == 200


# ---------- Public POST endpoints ----------
def test_booking_endpoint():
    s = requests.Session()
    token = _csrf(s, "/tour-packages/bali-rafting-eco-tour")
    r = s.post(BASE + "/booking",
               headers={"X-CSRF-TOKEN": token, "Accept": "application/json", "X-Requested-With": "XMLHttpRequest"},
               data={"type": "tour", "item_name": "TEST Booking", "name": "QA Tester", "phone": "0812345"})
    assert r.status_code == 201, r.text[:300]
    j = r.json()
    assert j.get("ok") is True and "id" in j


def test_newsletter_endpoint():
    s = requests.Session()
    token = _csrf(s, "/")
    r = s.post(BASE + "/newsletter",
               headers={"X-CSRF-TOKEN": token, "Accept": "application/json", "X-Requested-With": "XMLHttpRequest"},
               data={"email": "qa_test@example.com"})
    assert r.status_code == 200
    assert r.json().get("ok") is True


# ---------- Admin auth ----------
def _login(s):
    token = _csrf(s, "/admin/login")
    r = s.post(BASE + "/admin/login",
               headers={"X-CSRF-TOKEN": token},
               data={"_token": token, "username": "admin", "password": "admin12345"},
               allow_redirects=False)
    assert r.status_code in (302, 303), r.text[:300]
    return r


def test_admin_login_wrong_password():
    s = requests.Session()
    token = _csrf(s, "/admin/login")
    r = s.post(BASE + "/admin/login",
               data={"_token": token, "username": "admin", "password": "wrongpass"},
               allow_redirects=True)
    assert "Invalid username or password" in r.text or "invalid" in r.text.lower()


def test_admin_unauth_redirect():
    r = requests.get(BASE + "/admin", allow_redirects=False)
    assert r.status_code in (302, 303)
    assert "/admin/login" in r.headers.get("Location", "")


def test_admin_login_ok():
    s = requests.Session()
    _login(s)
    r = s.get(BASE + "/admin")
    assert r.status_code == 200


ADMIN_PAGES = [
    "/admin", "/admin/tours", "/admin/cars", "/admin/activities",
    "/admin/articles", "/admin/bookings", "/admin/settings",
    "/admin/content", "/admin/account", "/admin/tours/create", "/admin/tours/1/edit",
]


@pytest.mark.parametrize("path", ADMIN_PAGES)
def test_admin_pages(path):
    s = requests.Session()
    _login(s)
    r = s.get(BASE + path)
    assert r.status_code == 200, f"{path} -> {r.status_code}"
    assert "Whoops" not in r.text


# ---------- Admin CRUD (tours) ----------
def test_admin_tour_crud():
    s = requests.Session()
    _login(s)
    # Create
    token = _csrf(s, "/admin/tours/create")
    r = s.post(BASE + "/admin/tours",
               data={"_token": token, "title": "QA Tour Test", "price": "450000"},
               allow_redirects=True)
    assert r.status_code == 200, r.text[:300]
    # Find id from list
    list_html = s.get(BASE + "/admin/tours").text
    assert "QA Tour Test" in list_html
    ids = re.findall(r"/admin/tours/(\d+)/edit", list_html)
    assert ids
    new_id = ids[-1]
    # Edit
    edit_page = s.get(BASE + f"/admin/tours/{new_id}/edit").text
    tok2 = re.search(r'name="_token"\s+value="([^"]+)"', edit_page).group(1)
    r = s.post(BASE + f"/admin/tours/{new_id}",
               data={"_token": tok2, "_method": "PUT", "title": "QA Tour Edited", "price": "500000"},
               allow_redirects=True)
    assert r.status_code == 200
    assert "QA Tour Edited" in s.get(BASE + "/admin/tours").text
    # Delete
    tok3 = _csrf(s, "/admin/tours")
    r = s.post(BASE + f"/admin/tours/{new_id}",
               data={"_token": tok3, "_method": "DELETE"},
               allow_redirects=True)
    assert r.status_code == 200
    assert "QA Tour Edited" not in s.get(BASE + "/admin/tours").text


# ---------- Admin settings ----------
def _extract(page, name, default=""):
    m = re.search(r'name="' + re.escape(name) + r'"[^>]*value="([^"]*)"', page)
    return m.group(1) if m else default


def test_admin_settings_update():
    s = requests.Session()
    _login(s)
    page = s.get(BASE + "/admin/settings").text
    tok = re.search(r'name="_token"\s+value="([^"]+)"', page).group(1)
    orig = {
        "brand[name]": _extract(page, "brand[name]", "Bali Vision Tour"),
        "brand[title]": _extract(page, "brand[title]", "Bali Vision Tour"),
        "brand[tagline]": _extract(page, "brand[tagline]"),
        "brand[legal]": _extract(page, "brand[legal]"),
        "brand[logo]": _extract(page, "brand[logo]"),
        "brand[logoLight]": _extract(page, "brand[logoLight]"),
        "brand[logoMode]": _extract(page, "brand[logoMode]", "icon"),
        "brand[favicon]": _extract(page, "brand[favicon]"),
        "contact[whatsapp]": _extract(page, "contact[whatsapp]", "6281234567890"),
        "contact[phone]": _extract(page, "contact[phone]"),
        "contact[email]": _extract(page, "contact[email]", "info@example.com"),
        "contact[emailLink]": _extract(page, "contact[emailLink]"),
        "contact[address]": _extract(page, "contact[address]"),
        "contact[addressLink]": _extract(page, "contact[addressLink]"),
        "contact[whatsappMessage]": _extract(page, "contact[whatsappMessage]"),
        "social[instagram]": _extract(page, "social[instagram]"),
        "social[facebook]": _extract(page, "social[facebook]"),
        "social[youtube]": _extract(page, "social[youtube]"),
        "social[tiktok]": _extract(page, "social[tiktok]"),
    }
    payload = dict(orig)
    payload["_token"] = tok
    payload["_method"] = "PUT"
    payload["brand[name]"] = "QA Brand"
    payload["contact[whatsapp]"] = "6280000000000"
    r = s.post(BASE + "/admin/settings", data=payload, allow_redirects=True)
    assert r.status_code == 200, r.text[:500]
    home = requests.get(BASE + "/").text
    assert "QA Brand" in home
    # Restore
    tok2 = _csrf(s, "/admin/settings")
    restore = dict(orig)
    restore["_token"] = tok2
    restore["_method"] = "PUT"
    s.post(BASE + "/admin/settings", data=restore, allow_redirects=True)


# ---------- Admin content ----------
def test_admin_content_save_noop():
    s = requests.Session()
    _login(s)
    page = s.get(BASE + "/admin/content").text
    tok = re.search(r'name="_token"\s+value="([^"]+)"', page).group(1)
    r = s.post(BASE + "/admin/content",
               data={"_token": tok, "_method": "PUT"},
               allow_redirects=True)
    assert r.status_code == 200


# ---------- Admin preferences ----------
def test_admin_preferences_update():
    s = requests.Session()
    _login(s)
    tok = _csrf(s, "/admin/account")
    r = s.post(BASE + "/admin/preferences",
               data={"_token": tok, "_method": "PUT", "idle_timeout": "30"},
               allow_redirects=True)
    assert r.status_code == 200


# ---------- Admin upload ----------
def test_admin_upload():
    s = requests.Session()
    _login(s)
    tok = _csrf(s, "/admin")
    # Minimal PNG
    png = bytes.fromhex("89504e470d0a1a0a0000000d49484452000000010000000108060000001f15c4890000000d49444154789c6300010000000500010d0a2db40000000049454e44ae426082")
    r = s.post(BASE + "/admin/upload",
               headers={"X-CSRF-TOKEN": tok, "X-Requested-With": "XMLHttpRequest", "Accept": "application/json"},
               files={"file": ("t.png", io.BytesIO(png), "image/png")})
    assert r.status_code in (200, 201), r.text[:300]
    j = r.json()
    assert "url" in j and j["url"].startswith("/storage/uploads/")
    r2 = requests.get(BASE + j["url"])
    assert r2.status_code == 200


# ---------- Logout ----------
def test_admin_logout():
    s = requests.Session()
    _login(s)
    tok = _csrf(s, "/admin")
    r = s.post(BASE + "/admin/logout", data={"_token": tok}, allow_redirects=False)
    assert r.status_code in (302, 303)
    assert "/admin/login" in r.headers.get("Location", "")
