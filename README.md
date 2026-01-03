# 🎬 Wild Series — Symfony TV Show Database

Wild Series is a Symfony application that manages TV shows, seasons, episodes, comments, users, authentication, and an advanced search system.
This project was built as part of the Symfony learning 

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

## 🧩 Twig Components
- Navbar as a Twig Component (categories dropdown)
- `LastEpisode` Twig Component displayed on all pages (shows the last 3 created episodes)

## ⚡ Symfony UX Live Components
- `ProgramSearch` Live Component: live search without page reload

## ❤️ Watchlist (AJAX)
- ManyToMany relation between `User` and `Program` (`watchlist` join table)
- Toggle watchlist from the program page (heart icon)
- Uses `fetch()` to update the icon without reloading the page
- State is persisted in database (still correct after refresh)

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
```txt
wild-series/
 ├─ assets/
 │   ├─ controllers/
 │   │   ├─ hello_controller.js
 │   │   └─ theme_controller.js
 │   │
 │   ├─ images/
 │   │
 │   ├─ styles/
 │   │   └─ app.scss
 │   │
 │   ├─ vendor/
 │   │
 │   ├─ app.js
 │   ├─ bootstrap.js
 │   └─ controllers.json
 │
 │
 ├─ bin/
 │
 ├─ config/
 │   ├─ packages/
 │   ├─ routes/
 │   └─ services.yaml
 │
 ├─ migrations/
 │
 ├─ public/
 │   └─ index.php
 │
 ├─ src/
 │   ├─ Controller/
 │   │    ├─ ActorController.php
 │   │    ├─ CategoryController.php
 │   │    ├─ DefaultController.php
 │   │    ├─ EpisodeController.php
 │   │    ├─ ProgramController.php
 │   │    ├─ RegistrationController.php
 │   │    ├─ ResetPasswordController.php
 │   │    ├─ SeasonController.php
 │   │    └─ SecurityController.php
 │   │    
 │   │
 │   ├─ DataFixtures/
 │   │    ├─ ActorFixtures.php
 │   │    ├─ AppFixtures.php
 │   │    ├─ CategoryFixtures.php
 │   │    ├─ EpisodeFixtures.php
 │   │    ├─ ProgramFixtures.php
 │   │    ├─ SeasonFixtures.php
 │   │    └─ UserFixtures.php         
 │   │    
 │   │
 │   ├─ Entity/
 │   │    ├─ Actor.php
 │   │    ├─ Category.php
 │   │    ├─ Comment.php
 │   │    ├─ Episode.php
 │   │    ├─ Program.php
 │   │    ├─ ResetPasswordRequest.php
 │   │    ├─ Season.php
 │   │    └─ User.php
 │   │
 │   ├─ Form/
 │   │    ├─ ActorType.php
 │   │    ├─ CategoryType.php
 │   │    ├─ ChangePasswordFormType.php
 │   │    ├─ CommentType.php
 │   │    ├─ EpisodeType.php
 │   │    ├─ ProgramType.php
 │   │    ├─ RegistrationFormType.php
 │   │    ├─ ResetPasswordRequestFormType.php
 │   │    ├─ SearchProgramType.php
 │   │    └─ SeasonType.php
 │   │
 │   ├─ Repository/
 │   │    ├─ ProgramRepository.php
 │   │    ├─ ActorRepository.php
 │   │    ├─ CategoryRepository.php
 │   │    └─ UserRepository.php
 │   │
 │   ├─ Security/
 │   │    ├─ LoginFormAuthenticator.php
 │   │    └─ EmailVerifier.php
 │   │
 │   ├─ Service/
 │   │    └─ ProgramDuration.php
 │   │
 │   ├─ Twig/Components
 │   │    ├─ LastEpisode.php
 │   │    ├─ Navbar.php
 │   │    ├─ ProgramSearch.php
 │   │    └─ WatchList.php
 │   │
 │   └─ Kernel.php
 │
 ├─ templates/
 │   ├─ actor/
 │   │    ├─ edit.html.twig
 │   │    ├─ index.html.twig
 │   │    ├─ new.html.twig
 │   │    └─ show.html.twig
 │   │
 │   ├─ category/
 │   │    ├─ index.html.twig
 │   │    ├─ new.html.twig
 │   │    └─ show.html.twig
 │   │
 │   ├─ components/
 │   │    ├─ LastEpisode.html.twig
 │   │    ├─ Navbar.html.twig
 │   │    ├─ ProgramSearch.html.twig
 │   │    └─ WatchList.html.twig
 │   │
 │   ├─ emails/
 │   │    ├─ layout.html.twig
 │   │    └─ new_episode.html.twig
 │   │
 │   ├─ episode/
 │   │    ├─ _delete_form.html.twig
 │   │    ├─ _form.html.twig 
 │   │    ├─ edit.html.twig
 │   │    ├─ index.html.twig
 │   │    ├─ new.html.twig
 │   │    └─ show.html.twig
 │   │
 │   ├─ program/
 │   │    ├─ edit.html.twig
 │   │    ├─ episode_show.html.twig
 │   │    ├─ index.html.twig
 │   │    ├─ new.html.twig
 │   │    ├─ newProgramEmail.html.twig
 │   │    ├─ season_show.html.twig
 │   │    └─ show.html.twig
 │   │
 │   ├─ registration/
 │   │    └─ confirmation_email.html.twig
 │   │
 │   ├─ reset_password/
 │   │    ├─ check_email.html.twig
 │   │    ├─ email.html.twig
 │   │    ├─ request.html.twig
 │   │    └─ reset.html.twig
 │   │
 │   ├─ season/
 │   │    ├─_delete_form.html.twig
 │   │    ├─_form.html.twig
 │   │    ├─ edit.html.twig
 │   │    ├─ index.html.twig
 │   │    ├─ new.html.twig
 │   │    └─ show.html.twig
 │   │
 │   ├─ security/
 │   │    ├─ login.html.twig
 │   │    ├─ profile.html.twig
 │   │    └─ register.html.twig
 │   │
 │   ├─ _navbar.html.twig
 │   ├─ base.html.twig
 │   └─ index.html.twig
 │
 │
 ├─ var/
 ├─ vendor/
 ├─ composer.json
 ├─ symfony.lock
 ├─ .env
 └─ README.md

```

## ✅ Requirements
- PHP 8.3+ / 8.4
- Composer
- MySQL
- Symfony CLI (recommended)

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

## 🧠 Notes
- This project uses AssetMapper/importmap for frontend assets.
- Watchlist uses `fetch()` in `assets/app.js` to update the UI without reloading.