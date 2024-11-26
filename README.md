## Genel Bakış
Bu proje, her kullanıcının kendi profillerine PDF dosyalarını etkili bir şekilde yönetmek için tasarlanmış bir Laravel uygulamasıdır. PDF dosyalarını yüklemek, görüntülemek ve yönetmek için kontrolcüler ve yönlendirmeler içerir. Her kullanıcı sadece kendi pdf dosyaları üzerinde işlem yapabilir dışarıdan başkası erişim sağlayamaz.

## Kurulum
1. **Depoyu Klonlayın**
   ```bash
   git clone https://github.com/malisahin89/laravel-pdf-secure.git
   cd laravel-pdf-secure
   ```

2. **Bağımlılıkları Yükleyin**
   ```bash
   composer install
   npm install && npm run dev
   ```

3. **Ortam Ayarları**
   - `.env.example` dosyasını `.env` olarak kopyalayın:
     ```bash
     cp .env.example .env
     ```
   - Veritabanınızı ve diğer ortam değişkenlerini `.env` dosyasında yapılandırın.

4. **Uygulama Anahtarını Oluşturun**
   ```bash
   php artisan key:generate
   ```

5. **Veritabanı**
   ```bash
   php artisan migrate
   ```

## Kullanım
### 1. PDF Dosyalarını Yükleme
- PDF dosyalarını yüklemek için bir yönlendirme ayarlanmıştır.
- Bir PDF yüklemek için uygulamada sağlanan formu kullanabilir veya `/pdf/upload` adresine bir POST isteği gönderebilirsiniz.

### 2. PDF Dosyalarını Görüntüleme
- Uygulama, PDF dosyalarını tarayıcıda görüntüleme işlevine sahiptir.
- Belirli bir PDF dosyasını görüntülemek için `/pdf/view/{id}` uç noktasını kullanabilirsiniz.

## Yönlendirme Özeti
`web.php` dosyasında aşağıdaki yönlendirmeler yapılandırılmıştır:

- **`GET /pdf/view/{id}`**: PDF dosyasını tarayıcıda görüntüler.
- **`POST /pdf/upload`**: PDF dosyalarının yüklenmesini sağlar.

## Kontrolcüler ve Modeller
### Kontrolcüler
- **PDFViewController**: Bu kontrolcü, PDF dosyalarını görüntüleme işlemini yönetir.
  - **view()**: PDF dosyasını alır ve tarayıcıda yayınlar.

### Modeller
- **PdfFile**: Bu model, veritabanında saklanan PDF dosyalarını temsil eder ve dosya yolu ile adı gibi meta verileri içerir.

## Gereksinimler
- PHP >= 8.0
- Composer
- Laravel >= 9
- Node.js ve npm
- Bir veritabanı (örn. MySQL, SQLite)

## Uygulamayı Çalıştırma
Uygulamayı yerel olarak çalıştırmak için:
```bash
php artisan serve
```
Uygulama `http://127.0.0.1:8000` adresinde erişilebilir olacaktır.

## Sorun Giderme
- `.env` dosyasının veritabanınız ve diğer servisler için doğru yapılandırıldığından emin olun.
- Yüklenen dosyalar erişilebilir değilse `php artisan storage:link` komutunu çalıştırın.


