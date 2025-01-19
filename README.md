# SaruScan - README

Welcome to **SaruScan**! This is an online **comic/manga** reading platform allowing you to add, manage, and read chapters.


---

## 1. Prerequisites

- **PHP** >= 7.4
- **MySQL** 
---

## 2. Installation

### 2.1. Project Setup

1. **Clone** this repository or **download** the ZIP:
   ```bash
   git clone https://github.com/MassinissaGhoul/SaruScan.git
   ```
2. Place the project on your **local server** .

### 2.2. Database Configuration

1. In the `Documentation/Conception_BDD` folder, you will find a `saruscan.sql` file (and possibly other examples). **Import** that file into your DBMS to **create** the database structure (tables, columns, etc.):

   - Open, for example, **phpMyAdmin**, create a database named `saruscan`.
   - Import the `saruscan.sql` file or copy-paste its contents into the **SQL** tab.

2. **Check** the connection details in `src/php_folder/methode/db.php` and **update** as needed:
   ```php
   $host = 'localhost';
   $db = 'saruscan';
   $user = 'root';
   $pass = '';
   // ...
   ```
   Make sure these values match your local environment (username, password, DB name, etc.).

### 2.3. Running the Project

- Start your local server (XAMPP / WAMP / LAMP) Laragon is the best option.

- Access the website via your **browser**

---

## 3. Usage

### 3.1. Browsing Comics

- **Homepage**: `homepage.php`
  - Displays **recent comics** and **popular comics**.
  - Click on a comic to view its dedicated page and start reading.

### 3.2. Reading Chapters

- On a comic page (e.g., `comics_page.php?title=XYZ`), you will see the **chapter list**, plus **buttons** to read or download a chapter.
- The reading page (`test.php`) allows you to **read** page by page or in **Webtoon** mode.
- **NEXT** / **PREVIOUS** buttons automatically move to the next or previous chapter.

### 3.3. Admin Page (`admin_page.php`)

This requires **admin** privileges (`is_admin = 1` in the `user` table).

1. Log in with an **admin account**.
2. Go to `admin_page.php` admin button in header.

Once on the admin page:

#### 3.3.1. Adding a Comic

- Find the **"Add a Comic"** section.
- Provide:
  - **Title** (title_comics),
  - **Author** (author),
  - **Category** (category),
  - Optionally an **Image Path** (image_path).
- **Submit** by clicking “Add”.

The new comic will appear in the comics list on the same page.

#### 3.3.2. Adding a Chapter

- In the **"Add a Chapter"** section:
  - **Comic Name** (the exact existing title in the DB),
  - **Chapter Number**,
  - **Chapter Title**,
  - **Path to the Images** (where chapter pages are located).
- **Submit**.  

#### 3.3.3. Deleting a Comic

- In the **Comics List** (table):
  - Click **Delete** (the red button).
  - Confirm.  
  The comic and associated data will be removed from the database.

#### 3.3.4. Editing a Comic

- In the **Comics List** (table):
  - Click **Edit** (the blue button).
  - You’ll be redirected to `edit_comic.php` where you can **change** the title, author, or category.
  - **Submit** and you’ll be redirected back to the admin page.

#### 3.3.5. Managing Users

- On the same admin page, you will see a **Users list**:
  - **Delete** a user: red button
  - **Edit** a user: blue button  
    (redirects to `edit_user.php`)

---

## 4. Authentication & Account Management

### 4.1. Registration

- Go to `register.php`.
- Fill in the required fields (**email**, **username**, **password**).
- On success, you are **redirected** to the homepage (or login page).

### 4.2. Login

- Go to `login.php`.
- Enter your **email** and **password**.
- On success, you are redirected to `homepage.php`.

### 4.3. User Profile

- Page `profilepage.php`.
- Change username, email, password, **profile picture**, etc.
- Updates are applied to the `user` table.

---

## 5. Additional Features

- **Search**: The search bar in `header.php` redirects to `comics_research.php`, displaying comics matching the keyword.
- **Comments**: You can comment on comics and chapters if you’re logged in. Comments appear on `comics_page.php` and `test.php`.
- **Favorites**: Click **Add to Favorites** on a comic page. See your favorite comics on `favorites.php`.

---


## 7. Notes and Tips

1. **Security**:
   - Ensure only `admin` users can access `admin_page.php`.
   - Validate directory paths for chapter images so they do not allow arbitrary server access.

2. **Chapter Paths**:
   - Verify that your image folders are placed in the correct directory: `src/php_folder/comics/...`.
   - Each chapter’s folder is specified in the database as *comics_path*.

3. **Updates**:
   - When editing or deleting, ensure DB consistency (foreign keys, etc.).

---

