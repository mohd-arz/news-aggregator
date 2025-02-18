## News Aggregator  

### Docker Setup Instructions  

- Run `docker-compose up -d --build` to build the image and run it in detached mode.  
- Ensure the images are running using `docker-compose ps`:  
  1. `app` (Laravel application)  
  2. `db` (MySQL)  
  3. `phpmyadmin` (GUI for database management)  
  4. `redis` (Cache driver)  
  5. `nginx` (For serving files)  

- **Running Artisan Commands:**  
  - Prefix every Artisan command with `docker-compose exec app` to execute it inside the container.  

- **Setting Up the Environment:**  
  1. Open a new terminal and run:  
     ```sh
     mv .env.example .env
     ```  
  2. Update the `.env` file for Docker
     - Change `DB_HOST=db` (Docker service name)  
     - Change `CACHE_STORE=redis` (Docker service name)  
     - The rest of the file remains the same as when bootstrapping the app normally.
  3. Install dependencies and set up the database:  
     ```sh
     docker-compose exec app composer install
     docker-compose exec app php artisan migrate
     docker-compose exec app php artisan migrate:status  # Check migration status
     docker-compose exec app php artisan db:seed  # Seed database (User: test@example.com, Password: password)
     ```  
  4. Fetch and store news in the database for the first time:  
     ```sh
     docker-compose exec app php artisan app:fetch-news
     ```  
  5. Start queue worker:  
     ```sh
     docker-compose exec app php artisan queue:work
     ```  
  6. Trigger cron jobs locally to fetch news every hour:  
     ```sh
     docker-compose exec app php artisan schedule:work
     ```  

- **API Testing:**  
  - Use **Postman** to test authentication (`login`), fetch news, set preferences, and retrieve preferred news.  
  - Base API URL: `{hostname}/api/v1`  

### OpenAPI Docs  

- Visit `{hostname}/api/documentation` to access the full API documentation GUI.  
- JSON documentation available at `{hostname}/storage/api-docs.json`.  

---

## My Implementation  

### Docker Implementation  
I already had some experience with Docker and Docker with Node.js. Since Node.js has a built-in server for handling files, configuring a server isn't a big deal. However, with PHP, we need a dedicated file server to serve files.  

I referred to a **[DigitalOcean tutorial](https://www.digitalocean.com/community/tutorials/how-to-install-and-set-up-laravel-with-docker-compose-on-ubuntu-22-04)** to learn about setting up a Laravel Docker environment. While implementing it, I faced an issue: **"No such host!"** After troubleshooting, I found a solution on Docker’s GitHub issue page. It turns out that Docker changed its network service policies, and some ISPs haven’t resolved the issue yet. Switching to a different Wi-Fi network solved the problem.  

For caching, I chose **Redis** because it supports **tag-based cache invalidation**, allowing me to efficiently remove cache entries based on tags.  

### Laravel 11 Experience  
I have used Laravel **7 and 10** in my organization but had no hands-on experience with **Laravel 11** before this project. Initially, I faced difficulties, especially with:  
- **Middleware:** Adding new middleware and assigning aliases is not as straightforward as before.  
- **Exception Handling:** Laravel returns errors in **HTML format by default**, but for APIs, we need **JSON responses**. In previous versions, we could create a middleware to enforce `application/json` headers or use `expectsJson()`.  
- **Service Providers:** In Laravel 11, **providers are abstracted away**, so I had to implement middleware and exception handling directly in `bootstrap/app.php`.  

Despite the challenges, it was a great learning experience, and I enjoyed exploring the changes in Laravel 11.  

### API Free Plan Limitations  
When I first received this assignment, I experimented with various **news APIs** to understand their responses. I quickly realized that:  
1. Each API **returns different response formats**, so I had to **normalize** the data structure.  
2. Free-tier plans have limitations:  
   - They **don’t provide extensive past data**, making it hard to fetch historical news.  
   - The **variety of available news is limited**.  

Due to these restrictions, I designed the system to store **news from the time of first execution** to gradually build a local news archive.  

### Future Enhancements  
In the future, I aim to:  
- **Improve Testing**: Learn more about **Test-Driven Development (TDD)** and incorporate **better test coverage** beyond simple API tests.  
- **Enhance News Data Storage**: Improve the system to fetch and store **more past data** during the initial setup.  
- **Detect Duplicate News**: Implement a mechanism to identify and remove duplicate news based on **normalized titles**.  

---
