# 🎬 Wild Series — Symfony TV Show Database

Wild Series is a Symfony application that manages TV shows, seasons, episodes, comments, users, authentication, and an advanced search system.
This project was built as part of the Symfony learning path at the Wild Code School.

# 🚀 Main Features
## 🔐 Authentication & Security

- User registration via /register

- Login / Logout

- Automatic authentication after registration

- Role management: ROLE_USER, ROLE_CONTRIBUTOR, ROLE_ADMIN

- Access restrictions based on user role

- "My Profile" page available only when logged in

- CSRF protection on all forms

## 📝 Program Management

- Create new TV shows (contributors & admins only)

- Edit only the shows you own (admins can edit all)

- Automatic slug generation

- Image upload support (VichUploaderBundle)

## 💬 Comments System

- Only logged-in users can post comments

- Contributors can delete only their own comments

- Admins can delete any comment

- Comments displayed chronologically

## 🔍 Advanced Search (QueryBuilder)

- Search by program title

- Search by actor name

- Custom repository method using LEFT JOIN, OR WHERE, and LIKE

- A dedicated search form (SearchProgramType)

## 🗃️ Fixtures & Database

Automatic loading of:

- Categories

- Programs

- Seasons

- Episodes

- Actors

- Default users (contributor + admin)

Each program created through fixtures is automatically linked to an owner.

## 🛠️ Tech Stack

- Symfony 6/7

- Doctrine ORM

- Twig

- Bootstrap 5

- VichUploaderBundle

- Symfony Mailer

- MySQL

- QueryBuilder / DQL

## 📁 Project Structure (Simplified)
src/
 ├─ Controller/
 ├─ Entity/
 ├─ Form/
 ├─ Repository/
 ├─ Security/
 ├─ Service/
 └─ DataFixtures/

templates/
 ├─ program/
 ├─ episode/
 ├─ security/
 ├─ registration/
 └─ partials/
 

## 🧪 Running the Project
## 1️⃣ Install dependencies
git clone https://github.com/MateuszPlebanek/wild-series2.git
cd wild-series
composer install  

## 2️⃣ Configure environment variables

Create .env.local:

DATABASE_URL="mysql://USER:PASSWORD@127.0.0.1:3306/wild-series2"
MAILER_DSN=smtp://localhost

## 3️⃣ Create database + run migrations
symfony console doctrine:database:create
symfony console doctrine:migrations:migrate

## 4️⃣ Load fixtures
symfony console doctrine:fixtures:load

## 5️⃣ Start the server
symfony server:start -d