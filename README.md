Bước 1: Mở Terminal và thực hiện clone dự án và vào thư mục dự án bằng câu lệnh sau:
`git clone https://github.com/NguyenTuTrung/Web_INT3306_2.git`

Bước 2: Chạy composer và npm để cài đặt các gói cần thiết trong dự án
`composer install`
`npm install `

Bước 3: Tạo database và config database
Vào mysql workbech hoặc adminer tạo ra database mới
Ví dụ tạo database có tên là :laravel-5-boilerplate

Sau đó ta thực hiện lệnh sau để copy ra file env:

`cp .env.example .env`

Cập nhật file env của bạn như sau:

DB_CONNECTION=mysql          
DB_HOST=127.0.0.1            
DB_PORT=3306                 
DB_DATABASE=  
DB_USERNAME=root             
DB_PASSWORD=   

Bước 4: Tạo ra key cho dự án
`php artisan key:generate`

Bước 5: Tạo ra các bảng và dữ liệu mẫu cho database
`php artisan migrate`
`php artisan db:seed`

Bước 7: Xây dựng các styles và scripts
npm run <command>

Các lệnh(command) khả dụng được liệt kê ở đầu tệp package.json dưới từ khóa 'scripts'.

Bước8: Storage:link
`php artisan storage:link`

Sau khi cài dự án bạn phải chạy lệnh trên để public thư mục lưu trữ của bạn khi người dùng upload ảnh.

Bước 9: 
`php aritsan serve`

Các dự án khác cũng clone và thực hiện tương tự các bước như trên