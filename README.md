## Installation

Follow these steps for the testing process:
- Install composer [here] (https://getcomposer.org/download/)
- Make sure you read [the server requirements for codeigniter 4] (https://codeigniter.com/user_guide/intro/requirements.html) 
- Clone the repository using `git clone https://github.com/lotusCupcake/assessment-backend.git`
- Activate the extension related to the PotgresQL database in your PHP
- Set the database configuration by looking at and following the **Setup** below
- Run `php spark migrate`
- Run `composer install`
- Run `php spark serve`.

## Setup

Create a postgresql database on your server, copy `env` to `.env` and tailor for your app, specifically the baseURL
and any database settings. We recommend following the lines below:

`CI_ENVIRONMENT = production`

`app.baseURL = 'http://localhost:8080'`

`database.default.hostname = your_host`
`database.default.database = assessment_backend`
`database.default.username = your_username`
`database.default.password = your_password`
`database.default.DBDriver = Postgre`
`database.default.DBPrefix =`
`database.default.port = your_port`

