# Product Review System

A web application designed for managing products and customer reviews with admin moderation capabilities.

---

## 🚀 Features

### 👥 User Roles & Management
* **Admin**: Full access to product management and review moderation.
* **Reviewer**: Can submit reviews and ratings for listed products.

### 📦 Product Management (CRUD)
* **Create, Read, Update, Delete** products.
* Product attributes:
  * Name
  * Brand
  * Price
  * Specifications (Specs)

### ✍️ Product Reviews
* Submit feedback on products:
  * **Rating**: 1 to 5 stars
  * **Title**: Summary of the review
  * **Comment**: Detailed feedback

### 🛡️ Admin Moderation
* Moderate incoming customer reviews:
  * **Approve** reviews to make them visible to the public.
  * **Delete** inappropriate or fake reviews.

---

## 🗄️ Database Entities

| Entity | Description / Attributes |
| :--- | :--- |
| **`users`** | User roles (`Admin`, `Reviewer`), authentication info |
| **`products`** | `Name`, `Brand`, `Price`, `Specs` |
| **`reviews`** | `Rating` (1-5), `Title`, `Comment`, `Status` (Approved/Pending), foreign keys to `users` and `products` |
