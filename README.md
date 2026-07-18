# 🌍 Global Supply Chain Risk Intelligence Platform

**Platform Monitoring Risiko Rantai Pasok Global Berbasis Multi-API dan Analitik Data**

Platform komprehensif untuk memantau dan menganalisis risiko supply chain global menggunakan integrasi multi-API, visualisasi geospasial, dan sentiment analysis berbasis lexicon.

---

## 📋 Deskripsi Project

Platform ini dirancang untuk membantu perusahaan dalam:
- ✅ Mengelola risiko logistik
- ✅ Memantau kondisi cuaca ekstrem
- ✅ Menganalisis gangguan transportasi
- ✅ Mengamati kondisi ekonomi suatu negara
- ✅ Membantu pengambilan keputusan bisnis

---

## 🛠️ Teknologi yang Digunakan

### Backend
- **PHP 8.x**
- **Laravel 11**
- **MySQL / SQLite**

### Frontend
- **Bootstrap 5** / **Tailwind CSS**
- **AJAX**
- **JavaScript ES6**

### Visualisasi
- **Chart.js** - Grafik & Analitik
- **Leaflet.js** - Peta Interaktif Global

### API Integration
- **Open-Meteo API** - Data Cuaca Global
- **World Bank API** - GDP, Inflasi, Populasi, Ekspor, Impor
- **REST Countries API** - Data Negara, Mata Uang, Wilayah, Bahasa
- **ExchangeRate API** - Kurs Mata Uang Real-time
- **GNews API** - Berita Ekonomi & Logistik

---

## 🚀 Fitur Utama

### 1. 🗺️ Dashboard Peta Interaktif
- Visualisasi pelabuhan global dengan Leaflet.js
- Risk scoring engine dengan algoritma weighted
- Real-time weather monitoring per negara

### 2. 🏳️ Global Country Dashboard
- Pilih negara untuk analisis detail
- Tampilan GDP, Inflasi, Populasi, Mata Uang
- Data cuaca real-time
- Informasi ekonomi lengkap

### 3. 💵 Currency Impact Dashboard
- Konverter mata uang real-time
- Grafik trend nilai tukar (7 hari)
- Perbandingan mata uang terkuat/terlemah
- Update otomatis setiap jam

### 4. 📰 News Intelligence Dashboard
- Berita terkini: Logistics, Trade, Shipping, Economy
- **Lexicon-Based Sentiment Analysis**
- Filter kategori berita
- Summary sentimen (Positive/Neutral/Negative)

### 5. 📊 Data Visualization Dashboard
- Grafik GDP Trend (5 tahun)
- Grafik Inflation Trend (5 tahun)
- Currency Trend vs USD
- Risk Score Trend
- Export vs Import Comparison
- Risk Distribution (Pie Chart)

### 6. ⚖️ Country Comparison Engine
- Bandingkan 2 negara secara side-by-side
- Perbandingan: GDP, Inflasi, Risk, Weather, Currency
- Visualisasi komparatif dengan Chart.js

### 7. ⭐ Favorite Monitoring List (Watchlist)
- Simpan negara untuk dipantau
- Tambah catatan custom per negara
- CRUD functionality lengkap

### 8. ⚠️ Risk Scoring Engine
**Formula:**
```
Risk Score = Weather Risk + Inflation Risk + Political News Risk + Currency Risk
```
**Output:** Low Risk / Medium Risk / High Risk

### 9. 📄 REST API Endpoints
```
GET /api/countries          - List semua negara
GET /api/countries/{code}   - Detail negara
GET /api/ports             - List pelabuhan
GET /api/news              - Berita terkini
GET /api/currency          - Kurs mata uang
GET /api/risk              - Risk scores
GET /api/watchlist         - Watchlist user
```

---

## 📦 Instalasi

### Requirements
- PHP >= 8.1
- Composer
- Node.js & NPM (opsional untuk Vite)
- SQLite atau MySQL

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd supply-chain-risk
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Setup API Keys di .env**
```env
GNEWS_API_KEY=your_gnews_api_key
EXCHANGERATE_API_KEY=your_exchangerate_api_key
```

**Dapatkan API Keys Gratis:**
- GNews API: https://gnews.io/ (100 requests/day free)
- ExchangeRate API: https://www.exchangerate-api.com/ (1500 requests/month free)

5. **Setup Database**
```bash
# Jika menggunakan SQLite (default)
touch database/database.sqlite

# Run migrations
php artisan migrate --seed
```

6. **Jalankan Server**
```bash
php artisan serve
```

Buka browser: `http://localhost:8000`

---

## 🗄️ Database Structure

### Tables Utama:
- `users` - User authentication
- `countries` - Data negara
- `ports` - Data pelabuhan global
- `risk_scores` - Skor risiko per negara
- `news_cache` - Cache berita
- `watchlists` - Negara favorit user
- `sentiment_dictionaries` - Kamus sentiment analysis

---

## 🧪 Sentiment Analysis

**Metode: Lexicon-Based Sentiment Analysis**

Sistem menggunakan kamus kata positif dan negatif untuk menganalisis sentimen berita:

```php
// Contoh Positive Words
['growth', 'increase', 'profit', 'stable', 'improve', 'success']

// Contoh Negative Words
['war', 'crisis', 'inflation', 'delay', 'disaster', 'conflict']
```

**Algoritma:**
1. Tokenize artikel berita
2. Hitung jumlah kata positif dan negatif
3. Bandingkan skor
4. Output: Positive / Neutral / Negative

---

## 🎯 Use Case Scenario

**Studi Kasus:**
Sebuah perusahaan ingin mengimpor barang dari berbagai negara.

**Masalah:**
- Cuaca buruk dapat mengganggu pengiriman
- Nilai tukar mata uang berubah
- Konflik geopolitik meningkatkan risiko
- Kemacetan pelabuhan menyebabkan keterlambatan
- Inflasi suatu negara mempengaruhi biaya produksi

**Solusi:**
Platform ini menyediakan dashboard terpadu untuk memantau seluruh indikator tersebut secara real-time.

---

## 👤 Default Login

```
Email: admin@example.com
Password: password
```

*(Ubah setelah instalasi pertama)*

---

## 📸 Screenshots

### Dashboard Peta Interaktif
- Real-time port monitoring dengan Leaflet.js
- Risk scores calculation per country

### Country Dashboard
- Comprehensive country data
- Economic indicators
- Real-time weather

### Currency Dashboard
- Live exchange rates
- Historical trends (Chart.js)

### News Intelligence
- Latest logistics & economy news
- Sentiment analysis visualization

---

## 🔧 Konfigurasi Tambahan

### Caching
Platform menggunakan caching untuk optimasi:
- Country data: 24 jam
- Currency rates: 1 jam
- News: 1 jam
- Weather: 10 menit

### Rate Limiting
- API eksternal dibatasi sesuai free tier
- Implementasi caching untuk mengurangi API calls

---

## 🚨 Troubleshooting

### API Key Errors
Jika muncul error "API key not configured":
1. Pastikan API keys sudah di-set di `.env`
2. Clear config cache: `php artisan config:clear`
3. Restart server

### Database Issues
```bash
php artisan migrate:fresh --seed
```

### Permission Errors (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

---

## 📊 Perkiraan Skala Project

- ✅ 15-20 tabel database
- ✅ 30+ endpoints
- ✅ 6-7 API eksternal
- ✅ Dashboard analitik kompleks
- ✅ Sistem scoring dan prediksi
- ✅ Peta interaktif global

---

## 🎓 Kemampuan yang Ditunjukkan

Project ini memperlihatkan kemampuan dalam:
- ✅ Full Stack Development
- ✅ API Integration (Multi-API)
- ✅ Data Engineering
- ✅ Dashboard Analytics
- ✅ Geospatial Visualization
- ✅ Business Intelligence
- ✅ Decision Support System
- ✅ Sentiment Analysis (NLP dasar)

---

## 📝 Development Notes

### Next Features (Future Roadmap)
- [ ] Historical data tracking
- [ ] Email alerts untuk high-risk countries
- [ ] Export data ke Excel/CSV
- [ ] Mobile responsive optimization
- [ ] Multi-language support
- [ ] Advanced ML risk prediction
- [ ] Real-time port congestion data

---

## 📄 License

This project is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

---

## 👨‍💻 Author

**Supply Chain Risk Intelligence Platform**
- Developed as Final Project
- Laravel 11 + Multi-API Integration
- 2024

---

## 🙏 Acknowledgments

- **Laravel** - PHP Framework
- **Leaflet.js** - Interactive Maps
- **Chart.js** - Data Visualization
- **Open-Meteo** - Weather Data
- **World Bank** - Economic Data
- **REST Countries** - Country Data
- **GNews** - News API
- **ExchangeRate-API** - Currency Data

---

## 📞 Support

Jika ada pertanyaan atau issue, silakan buat issue di repository atau hubungi developer.

---

**Happy Coding! 🚀**
