# Workflow

index.php

```
if (user not logged in) {
    redirect to login.php
} else {

}
```

login.php

```
if (user already logged in) {
    redirect to index.php
} else {
    switch (option) {
        case: Go to Registraion page {
            Redirect to signup.php
        }

        case: login {
            check username and password validation
            Ajax: /includes/login.php
        }
    }
}
```

signup.php

```
if (user already logged in) {
    redirect to index.php
} else {
    switch (option) {
        case: Go to login page {
            Redirect to login.php
        }

        case: Enter JNTU Number {
            Validate JNTU Number
            if (validation == success) {
                Ajax: Send OTP (/includes/send_otp.php)
            }
            TODO:
            if (Ajax == success) {
                validate otp from database
                create new password and insert in database
                redirect to login.php
            }
        }
    }
}
```

/includes/send_otp.php

```
function send_otp(email, name, otp) {
    sends mail using PHP mailer
}

if (user not exists in database) {
    Redirect to signup.php?msg=USER NOT FOUND
} else {
    Insert otp and expiration time into database
    send_otp()
}
```
