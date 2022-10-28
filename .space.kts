/**
* JetBrains Space Automation
* This Kotlin-script file lets you automate build activities
* For more info, see https://www.jetbrains.com/help/space/automation.html
*/

job("Laravel API") {
    startOn {
        codeReviewOpened{}

        gitPush {
            branchFilter {
                +Regex("feature")
                +Regex("ci")
            }
            // run only if there's a release tag
            // e.g., release/v1.0.0
            tagFilter {
                +"release/*"
            }
        }
    }

    container(displayName = "Laravel Test", image = "richarvey/nginx-php-fpm:2.0.1") {

        env["DB_CONNECTION"] = "pgsql"
        env["DB_HOST"] = "db"
        env["DB_PORT"] = "5432"
        env["DB_DATABASE"] = Secrets("postgres_db")
        env["DB_USERNAME"] = Secrets("postgres_db")
        env["DB_PASSWORD"] = Secrets("postgres_password")

        env["CUSTOM_TOKEN"] = Secrets("custom_token")
        env["RECAPTCHAV3_SITEKEY"] = Secrets("recaptchav3_sitekey")
        env["RECAPTCHAV3_SECRET"] = Secrets("recaptchav3_secret")
        env["CONFIG_EMAIL_HOST"] = Secrets("config_email_host")
        env["CONFIG_EMAIL_SENDER"] = Secrets("config_email_sender")
        env["CONFIG_EMAIL_PASSWORD"] = Secrets("config_email_password")
        env["CONFIG_EMAIL_NAME_USER"] = Secrets("config_email_name_user")


        service("postgres:14.5") {
            alias("db")
            env["POSTGRES_PASSWORD"] = Secrets("postgres_password")
            env["POSTGRES_DB"] = Secrets("postgres_db")
        }

        shellScript {
            content = """
                echo Run start...
                chmod +x ./scripts/00-laravel-deploy.sh
                ./scripts/00-laravel-deploy.sh
            """
        }
    }
}
