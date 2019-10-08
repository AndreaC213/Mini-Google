# Mini-Google-Finance
full-stack Development and Analytics

# StockServer

This is a multithreaded server for fetching stock prices. It maintains a hash
of the Dow 30 and Nifty 50 by periodically web-scraping finance sites.

## Requirements

**v1.0 3a**: Stock prices should be fetched by a Java component which will listen on a
specific TCPIP port and fetch the price of the stock from Yahoo or Google.  (We
will test this component separately.) This component will accept text input of
the form ‘TICKER,DATE’ and it will respond with the price as ‘USD,200.92’ or
‘INR,1822.92’ Your system must use this component to fetch all stock prices. Later
in the term you will be supplied with another team/s component which will do the
same thing.

# TakeStock Website

In this directory you will find scripts for deployment on AFS and other utiliites.
Inside the `takestock` directory you will find:

* `config.php` should be loaded first for any standalone page. It sets up
the include path and some general variables
* `index.php` main view for the website
* `signup.php` sign up form for the website
* `signin.php` sign in form from the website
* `actions` standalone pages that are called with parameters from forms.
* `include` snippets of code, functions, and classes for the website
* `templates` mostly HTML that is rendered on multiple pages

# Runner

This is a system for running R scripts on web.njit.edu. It consists of a bash
script to run Rscript taking input from the public_html/UPLOADS directory and
writing output to that same directory. For PHP configuration reasons, Runner
must exist within the public_html directory and can only write files to the
UPLOADS directory. PHP can only write files to the UPLOADS directory as well.

With this setup you can pass information to and from PHP and R via files.

Unfortunately web.njit.edu has an older version of glibc than
afsacces1.njit.edu, so it can't run the same lpSolve library. A version
compiled for web.njit.edu is included and referenced in the scripts.
