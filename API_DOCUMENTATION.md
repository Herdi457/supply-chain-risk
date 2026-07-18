# 📡 API Documentation

**Global Supply Chain Risk Intelligence Platform - REST API**

---

## 🔐 Authentication

Semua API endpoint memerlukan autentikasi melalui Laravel session. User harus login terlebih dahulu.

---

## 📍 Base URL

```
http://localhost:8000/api
```

---

## 🌍 Countries API

### 1. Get All Countries

**Endpoint:** `GET /api/countries`

**Description:** Mendapatkan daftar semua negara dari REST Countries API

**Query Parameters:**
- `search` (optional) - Filter berdasarkan nama negara
- `limit` (optional) - Jumlah maksimal hasil (default: 20)

**Request Example:**
```bash
GET /api/countries?search=indonesia&limit=10
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "name": "Indonesia",
      "official_name": "Republic of Indonesia",
      "code": "ID",
      "code3": "IDN",
      "capital": "Jakarta",
      "region": "Asia",
      "subregion": "South-Eastern Asia",
      "population": 273753191,
      "area": 1904569,
      "currencies": {
        "IDR": {
          "name": "Indonesian rupiah",
          "symbol": "Rp"
        }
      },
      "languages": {
        "ind": "Indonesian"
      },
      "flag": "https://flagcdn.com/w320/id.png",
      "lat": -5,
      "lng": 120
    }
  ],
  "total": 1
}
```

---

### 2. Get Country Detail

**Endpoint:** `GET /api/countries/{code}`

**Description:** Mendapatkan detail lengkap negara termasuk data ekonomi dari World Bank dan cuaca dari Open-Meteo

**Path Parameters:**
- `code` (required) - Kode negara ISO 2-letter (contoh: ID, US, DE)

**Request Example:**
```bash
GET /api/countries/ID
```

**Response:**
```json
{
  "success": true,
  "data": {
    "basic_info": {
      "name": "Indonesia",
      "official_name": "Republic of Indonesia",
      "code": "ID",
      "code3": "IDN",
      "capital": "Jakarta",
      "region": "Asia",
      "subregion": "South-Eastern Asia",
      "population": 273753191,
      "area": 1904569,
      "flag": "https://flagcdn.com/w320/id.png",
      "coordinates": [-5, 120]
    },
    "currencies": {
      "IDR": {
        "name": "Indonesian rupiah",
        "symbol": "Rp"
      }
    },
    "languages": {
      "ind": "Indonesian"
    },
    "economic_data": {
      "gdp": 1186093000000,
      "inflation": 3.02,
      "exports": 231539000000,
      "imports": 210669000000
    },
    "weather": {
      "temperature_2m": 28.5,
      "relative_humidity_2m": 75,
      "precipitation": 0,
      "rain": 0,
      "wind_speed_10m": 12.5,
      "weather_code": 1
    },
    "risk_score": {
      "total_score": 32.5,
      "level": "Medium Risk",
      "last_updated": "2024-07-16T10:30:00.000000Z"
    }
  }
}
```

---

## 🚢 Ports API

### Get All Ports

**Endpoint:** `GET /api/ports`

**Description:** Mendapatkan daftar pelabuhan global

**Query Parameters:**
- `country` (optional) - Filter berdasarkan kode negara
- `search` (optional) - Search berdasarkan nama pelabuhan atau negara
- `limit` (optional) - Jumlah maksimal hasil (default: 50)

**Request Example:**
```bash
GET /api/ports?country=ID&limit=10
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "port_name": "Tanjung Priok",
      "country_name": "Indonesia",
      "country_code": "ID",
      "latitude": -6.1,
      "longitude": 106.88,
      "created_at": "2024-07-16T10:00:00.000000Z",
      "updated_at": "2024-07-16T10:00:00.000000Z"
    }
  ],
  "total": 1
}
```

---

## 📰 News API

### Get News

**Endpoint:** `GET /api/news`

**Description:** Mendapatkan berita terkini dari GNews API

**Query Parameters:**
- `topic` (optional) - Topik berita (default: "supply chain")
- `lang` (optional) - Bahasa berita (default: "en")
- `limit` (optional) - Jumlah artikel (default: 10)

**Available Topics:**
- supply chain
- logistics
- trade
- shipping
- economy
- inflation
- port congestion

**Request Example:**
```bash
GET /api/news?topic=logistics&limit=5
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "title": "Global Supply Chain Disruption Eases",
      "description": "Major ports report reduced congestion...",
      "content": "Full article content...",
      "url": "https://example.com/article",
      "image": "https://example.com/image.jpg",
      "publishedAt": "2024-07-16T08:00:00Z",
      "source": {
        "name": "Reuters",
        "url": "https://reuters.com"
      }
    }
  ],
  "total": 5
}
```

**Error Response (API Key not set):**
```json
{
  "success": false,
  "message": "GNews API key not configured. Please set GNEWS_API_KEY in .env file"
}
```

---

## 💱 Currency API

### Get Currency Rates

**Endpoint:** `GET /api/currency`

**Description:** Mendapatkan nilai tukar mata uang real-time dari ExchangeRate API

**Query Parameters:**
- `base` (optional) - Mata uang dasar (default: USD)
- `target` (optional) - Target mata uang spesifik

**Request Example 1 (All Rates):**
```bash
GET /api/currency?base=USD
```

**Response:**
```json
{
  "success": true,
  "data": {
    "base": "USD",
    "rates": {
      "EUR": 0.92,
      "GBP": 0.79,
      "JPY": 149.50,
      "IDR": 15420.00,
      "CNY": 7.24
    },
    "last_updated": "Wed, 16 Jul 2024 12:00:01 +0000"
  }
}
```

**Request Example 2 (Specific Rate):**
```bash
GET /api/currency?base=USD&target=IDR
```

**Response:**
```json
{
  "success": true,
  "data": {
    "base": "USD",
    "target": "IDR",
    "rate": 15420.00,
    "last_updated": "Wed, 16 Jul 2024 12:00:01 +0000"
  }
}
```

---

## ⚠️ Risk Scores API

### Get Risk Scores

**Endpoint:** `GET /api/risk`

**Description:** Mendapatkan risk scores untuk semua negara

**Query Parameters:**
- `level` (optional) - Filter by risk level (Low Risk / Medium Risk / High Risk)
- `sort` (optional) - Sort order: asc / desc (default: desc)
- `limit` (optional) - Jumlah maksimal hasil (default: 50)

**Request Example:**
```bash
GET /api/risk?level=High Risk&limit=10
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "country_id": "SY",
      "country_code": "SY",
      "total_risk_score": 78.5,
      "risk_level": "High Risk",
      "weather_risk": 15.2,
      "inflation_risk": 25.5,
      "political_risk": 30.8,
      "currency_risk": 7.0,
      "created_at": "2024-07-16T10:00:00.000000Z",
      "updated_at": "2024-07-16T10:30:00.000000Z",
      "country": {
        "name": "Syria",
        "code": "SY"
      }
    }
  ],
  "total": 1
}
```

---

### Calculate Risk for Country

**Endpoint:** `GET /api/risk/{code}`

**Description:** Menghitung atau update risk score untuk negara tertentu

**Path Parameters:**
- `code` (required) - Kode negara ISO 2-letter

**Request Example:**
```bash
GET /api/risk/ID
```

**Response:**
```json
{
  "success": true,
  "message": "Risk score calculated successfully for Indonesia",
  "data": {
    "country_code": "ID",
    "total_risk_score": 32.5,
    "risk_level": "Medium Risk",
    "components": {
      "weather_risk": 8.5,
      "inflation_risk": 12.0,
      "political_risk": 8.0,
      "currency_risk": 4.0
    }
  }
}
```

---

## ⭐ Watchlist API

### Get User Watchlist

**Endpoint:** `GET /api/watchlist`

**Description:** Mendapatkan daftar negara favorit user yang sedang login

**Request Example:**
```bash
GET /api/watchlist
```

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "user_id": 1,
      "country_code": "ID",
      "country_name": "Indonesia",
      "notes": "Important supplier location",
      "created_at": "2024-07-16T10:00:00.000000Z",
      "updated_at": "2024-07-16T10:00:00.000000Z"
    }
  ]
}
```

---

### Add Country to Watchlist

**Endpoint:** `POST /api/watchlist`

**Description:** Menambahkan negara ke watchlist

**Request Body:**
```json
{
  "country_code": "ID",
  "country_name": "Indonesia",
  "notes": "Important supplier location"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Country added to watchlist",
  "data": {
    "id": 1,
    "user_id": 1,
    "country_code": "ID",
    "country_name": "Indonesia",
    "notes": "Important supplier location",
    "created_at": "2024-07-16T10:00:00.000000Z",
    "updated_at": "2024-07-16T10:00:00.000000Z"
  }
}
```

---

### Update Watchlist

**Endpoint:** `PUT /api/watchlist/{id}`

**Description:** Update notes untuk watchlist item

**Path Parameters:**
- `id` (required) - Watchlist item ID

**Request Body:**
```json
{
  "notes": "Updated notes here"
}
```

**Response:**
```json
{
  "success": true,
  "message": "Watchlist updated",
  "data": {
    "id": 1,
    "notes": "Updated notes here"
  }
}
```

---

### Remove from Watchlist

**Endpoint:** `DELETE /api/watchlist/{id}`

**Description:** Menghapus negara dari watchlist

**Path Parameters:**
- `id` (required) - Watchlist item ID

**Response:**
```json
{
  "success": true,
  "message": "Country removed from watchlist"
}
```

---

## 🔄 Caching Strategy

Untuk optimasi performa, semua API menggunakan caching:

| API | Cache Duration |
|-----|----------------|
| Countries | 24 hours |
| Country Detail | 24 hours |
| Currency Rates | 1 hour |
| News | 1 hour |
| Weather | 10 minutes |
| Ports | Permanent (database) |

---

## ⚡ Rate Limits

Free tier limits untuk external APIs:

| API | Limit |
|-----|-------|
| GNews | 100 requests/day |
| ExchangeRate | 1,500 requests/month |
| Open-Meteo | Unlimited (fair use) |
| REST Countries | Unlimited |
| World Bank | Unlimited |

**Best Practice:** Gunakan caching untuk mengurangi API calls

---

## 🚨 Error Handling

### Standard Error Response:
```json
{
  "success": false,
  "message": "Error message here"
}
```

### Common HTTP Status Codes:
- `200` - Success
- `400` - Bad Request
- `401` - Unauthorized
- `404` - Not Found
- `500` - Internal Server Error

---

## 📝 Notes

1. Semua endpoint menggunakan **Laravel CSRF protection**
2. Timestamps menggunakan format **ISO 8601**
3. Semua numeric values dalam **float/double** untuk presisi
4. Currency amounts dalam **USD** kecuali disebutkan lain

---

## 🔧 Testing with cURL

### Example: Get Country Detail
```bash
curl -X GET "http://localhost:8000/api/countries/ID" \
  -H "Accept: application/json" \
  --cookie "laravel_session=YOUR_SESSION_COOKIE"
```

### Example: Add to Watchlist
```bash
curl -X POST "http://localhost:8000/api/watchlist" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "X-CSRF-TOKEN: YOUR_CSRF_TOKEN" \
  --cookie "laravel_session=YOUR_SESSION_COOKIE" \
  -d '{
    "country_code": "ID",
    "country_name": "Indonesia",
    "notes": "Test note"
  }'
```

---

**Last Updated:** July 16, 2024
