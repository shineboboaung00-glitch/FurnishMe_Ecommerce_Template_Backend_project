# 🛒 E-Commerce Management System (Dynamic CRUD & PHP)

PHP, PDO နှင့် Vanilla JavaScript တို့ကို အသုံးပြု၍ Dynamic Modal/Form Architecture စနစ်ဖြင့် ရေးသားထားသော E-Commerce Web Application ဖြစ်ပါသည်။ Admin အသုံးပြုသူများအတွက် Categories နှင့် Products များကို Modal တစ်ခုတည်းမှတဆင့် Dynamic နည်းလမ်းဖြင့် Create, Update, Delete ပြုလုပ်နိုင်အောင် စနစ်တကျ တည်ဆောက်ထားပါသည်။

---

## 📖 Table of Contents

- [Features (ပါဝင်သော လုပ်ဆောင်ချက်များ)](#-features-ပါဝင်သော-လုပ်ဆောင်ချက်များ)

- [📂 Project Structure (ဖိုင် တည်ဆောက်ပုံ)](#-project-structure-ဖိုင်-တည်ဆောက်ပုံ)

- [🗄️ Database Setup (ဒေတာဘေ့စ် ပြင်ဆင်ခြင်း)](#-database-setup-ဒေတာဘေ့စ်-ပြင်ဆင်ခြင်း)

- [🔑 Admin Account Credentials (အဓိက အကောင့် အချက်အလက်များ)]

(#-admin-account-credentials-အဓိက-အကောင့်-အချက်အလက်များ)

- [🛠️ Dynamic Modal Usage (အသုံးပြုပုံ နမူနာ)](#-dynamic-modal-usage-အသုံးပြုပုံ-နမူနာ)

- [🚀 Getting Started (စတင်အသုံးပြုပုံ)](#-getting-started-စတင်အသုံးပြုပုံ)

---

## 📌 Features (ပါဝင်သော လုပ်ဆောင်ချက်များ)

- **Role-based Auth Check:** Admin User သာလျှင် Management Buttons (Add/Update/Delete) များကို မြင်တွေ့နိုင်ပြီး Dynamic Action များကို လုပ်ဆောင်နိုင်ပါသည်။

- **Dynamic Modal Architecture:** Form တစ်ခုချင်းစီအတွက် Modal သီးသန့်ရေးရန် မလိုဘဲ `openDynamicModal()` JavaScript function တစ်ခုတည်းဖြင့် Categories နှင့် Products CRUD လုပ်ဆောင်ချက်အားလုံးကို စီမံခန့်ခွဲပေးပါသည်။

- **Safe JSON Config Passing:** Data နှင့် Dynamic Fields များကို PHP JSON Safe Encoding (`JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES`) ပြုလုပ်၍ Frontend သို့ ဘေးကင်းစွာ ပေးပို့ထားပါသည်။

- **PDO Database Connection:** Security နှင့် Performance ကောင်းမွန်စေရန် Object-Oriented PHP & PDO Database Connection ကို အသုံးပြုထားပါသည်။

- **Flexible Image Handling:** Image Path မရှိပါက `static/default.jpg` သို့ Default ပေးပို့ပြီး Uploaded Images များကို စနစ်တကျ ဖော်ပြပေးပါသည်။

---

## 📂 Project Structure (ဖိုင် တည်ဆောက်ပုံ)

```plaintext
ecommerce-project/
├── admin/                         # Admin Management Views
│   ├── blogs.php                  # Admin Blog Management
│   ├── categories.php             # Admin Category Management
│   ├── dashboard.php              # Admin Dashboard Home
│   ├── messages.php               # Admin Contact Messages View
│   ├── newsletter.php             # Admin Newsletter Subscriptions View
│   ├── products.php               # Admin Product Management
│   ├── services.php               # Admin Service Management
│   └── team.php                   # Admin Team Management
├── classes/                       # OOP Model Classes & Business Logic
│   ├── base_model.php             # Base Model Class (Parent Class)
│   ├── blog.php                   # Blog Class
│   ├── category.php               # Category Class
│   ├── contact.php                # Contact Message Class
│   ├── newsletter.php             # Newsletter Class
│   ├── product.php                # Product Class
│   ├── service.php                # Service Class
│   └── team.php                   # Team Class
├── components/                    # UI Components & Reusable Snippets
│   ├── connection.php             # Database PDO Connection
│   ├── css.php                    # CSS Files Link Imports
│   ├── dashboard_header.php       # Admin Dashboard Header
│   ├── dashboard_sidebar.php      # Admin Dashboard Sidebar
│   ├── dynamic_form.php           # Dynamic Modal/Form Component
│   ├── footer.php                 # Global Footer
│   ├── header.php                 # Global Header
│   ├── js.php                     # JavaScript Link Imports
│   └── navbar.php                 # Global Navigation Bar
├── controllers/                   # Backend Action Controllers
│   └── process.php                # Central Form Action Processor
├── css/                           # Stylesheets
│   └── style.css                  # Main Custom CSS File
├── database/                      # Database Backup Files
│   └── ecommerce_db.sql           # Database Export File (Tables & Data)
├── js/                            # Frontend Scripts
│   └── script.js                  # Main JS (Modal & Dynamic Form Logic)
├── media/                         # Media Files / Static Images
│   └── product-2.png              # Sample Product Media
├── middleware/                    # Middleware Scripts
│   └── auth.php                   # Authentication & Access Control Check
├── static/                        # System Default Static Assets
│   └── default.jpg                # Fallback Image File
├── traits/                        # Reusable PHP Traits
│   └── uploadtrait.php            # File Upload Trait Logic
├── uploads/                       # Dynamic Uploaded Files Directory
├── about.php                      # About Us Page
├── blog.php                       # Public Blog Page
├── contact.php                    # Public Contact Page
├── index.php                      # Homepage
├── login.php                      # Login Page
├── logout.php                     # Logout Handler Page
├── register.php                   # Register Page
├── shop.php                       # Public Shop Page (Products Showcase)
├── team.php                       # Public Team Page
└── README.md                      # Project Documentation
```

---

## 🗄️ Database Setup (ဒေတာဘေ့စ် ပြင်ဆင်ခြင်း)

ပရောဂျက်တွင် ပူးတွဲပါဝင်သော **`database/ecommerce_db.sql`** ဖိုင်ကို အသုံးပြု၍ Database ကို အလွယ်တကူ Setup ပြုလုပ်နိုင်ပါသည်။

1. **phpMyAdmin** သို့မဟုတ် မိမိအသုံးပြုသော Database Management Tool ကို ဖွင့်ပါ။
2. Database အသစ်တစ်ခု ပြုလုပ်ပါ (ဥပမာ - `ecommerce_db`)။
3. ဖန်တီးလိုက်သော Database ကို ရွေးချယ်ပြီး **Import** Tab သို့ သွားပါ။
4. `database/` Folder ထဲတွင် ပါဝင်သော **`ecommerce_db.sql`** ဖိုင်ကို ရွေးချယ်၍ Import ပြုလုပ်ပါ။
5. `components/connection.php` တွင် မိမိ Local Server ၏ Database Connection အချက်အလက်များကို ပြင်ဆင်ပါ:
   ```php
   $host = "localhost";
   $user = "root";
   $pass = "";
   $db   = "ecommerce_db";
   ```

---

## 🔑 Admin Account Credentials (အဓိက အကောင့် အချက်အလက်များ)

`ecommerce_db.sql` ထဲတွင် Admin အကောင့်ကို တစ်ခါတည်း သွင်းပေးထားပြီး ဖြစ်ပါသည်။ Dynamic Action များနှင့် Management Buttons များကို စမ်းသပ်နိုင်ရန် အောက်ပါ အချက်အလက်များဖြင့် Login ဝင်ရောက်နိုင်ပါသည်။

| Account Role | Username / Email | Default Password | Access Level |
| :--- | :--- | :--- | :--- |
| **Super Admin** | `admin@example.com` | `admin123` | Full Admin & CRUD Access |

> ⚠️ **Note:** System ကို Production Server သို့ တင်သည့်အခါ သို့မဟုတ် စမ်းသပ်ပြီးပါက Admin Password ကို ချက်ချင်း လဲလှယ် ပြင်ဆင်ပေးပါ။

---

## 🛠️ Dynamic Modal Usage (အသုံးပြုပုံ နမူနာ)

Admin Dynamic Form ခေါ်ယူရန်အတွက် `openDynamicModal` သို့ အောက်ပါ Configuration JSON ပေးပို့၍ အသုံးပြုပါသည်။

### 1. Add New Category / Product (Create Action)
```javascript
openDynamicModal({
    module: 'categories',
    action: 'create',
    title: 'Add New Category',
    fields: [
        { name: 'name', label: 'Category Name', type: 'text', placeholder: 'Enter category name' },
        { name: 'image', label: 'Category Image', type: 'file' }
    ]
});
```

### 2. Edit & Delete Action Config (PHP Safe Encoding)
PHP Code အတွင်း Dynamic Update Configuration ပြုလုပ်ပုံ:

```php
$cat_update_config = json_encode([
    'module' => 'categories',
    'action' => 'update',
    'title'  => 'Edit Category',
    'fields' => [
        ['name' => 'name', 'label' => 'Category Name', 'type' => 'text'],
        ['name' => 'image', 'label' => 'New Image (Optional)', 'type' => 'file']
    ],
    'data'   => [
        'id'        => (string)$id,
        'name'      => $name,
        'old_image' => $raw_c_image
    ]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

$cat_delete_config = json_encode([
    'module'  => 'categories',
    'action'  => 'delete',
    'title'   => 'Delete Category',
    'message' => 'Are you sure you want to delete ' . $name . '?',
    'data'    => ['id' => (string)$id]
], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

```


---

## 🚀 Getting Started (စတင်အသုံးပြုပုံ)

### Prerequisites:
- **XAMPP / WAMP / MAMP** (PHP 7.4 သို့မဟုတ် ပိုမိုမြင့်မားသော Version)
- **MySQL Database**
- **Modern Web Browser**

### Execution Steps:
1. Project Folder တစ်ခုလုံးကို Local Server ၏ `htdocs` (သို့မဟုတ် `www`) folder အတွင်းသို့ ကူးယူပါ။
2. အထက်ပါ [Database Setup](#-database-setup-ဒေတာဘေ့စ်-ပြင်ဆင်ခြင်း) အတိုင်း `database/ecommerce_db.sql` ကို Import လုပ်ပါ။
3. Browser တွင် အောက်ပါ URL အတိုင်း ဖွင့်၍ အသုံးပြုနိုင်ပါပြီ:
   ```text
   http://localhost/your-project-folder/index.php
   ```
4. Admin အကောင့်ဖြင့် Login ဝင်ရောက်ကာ Dynamic Modal / CRUD လုပ်ဆောင်ချက်များကို စမ်းသပ်နိုင်ပါသည်။
