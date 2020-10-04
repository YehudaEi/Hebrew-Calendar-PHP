<html lang="he" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <title>מחשבון תאריכים עבריים</title> <!-- Jewish Calendar -->
        <meta name="author" content="Yehuda Eisenberg">
        <meta name="keywords" content="תאריך עברי, לוח שנה עברי, חישוב תאריך עברי, תאריך לועזי לתאריך עברי">
        <meta name="description" content="מחשבון פשוט להמרה של תאריך לועזי לתאריך עברי">
        <meta property="og:site_name" content="מחשבון תאריכים עבריים">
        <meta property="og:title" content="מחשבון תאריכים עבריים">
        <meta property="og:url" content="https://yehudae.net/JewishCalendar/">
        <meta property="og:description" content="מחשבון פשוט להמרה של תאריך לועזי לתאריך עברי">
        <link rel="icon" href="logo.png" type="image/png">

        <!-- Bootstrap -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
        <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>

        <!-- My JS && CSS -->
        <script src="js.js"></script>
        <link rel="stylesheet" href="css.css" />
        <script type="text/javascript">var _paq=window._paq||[];_paq.push(["setDocumentTitle",document.title]),_paq.push(["trackPageView"]),_paq.push(["enableLinkTracking"]),_paq.push(["trackVisibleContentImpressions"]),function(){var e="//stats.yehudae.net/";_paq.push(["setTrackerUrl",e+"m.php"]),_paq.push(["setSiteId","1"]);var t=document,a=t.createElement("script"),s=t.getElementsByTagName("script")[0];a.type="text/javascript",a.async=!0,a.defer=!0,a.src=e+"m.js",s.parentNode.insertBefore(a,s)}();</script>
    </head>
    <body>
        <br><br>
        <div class="container text-right">
            <div class="d-flex justify-content-between">
                <p class="m-0">בס"ד</p>
                <p class="m-0"><?php echo iconv ('WINDOWS-1255', 'UTF-8', jdtojewish(unixtojd(), true, CAL_JEWISH_ADD_GERESHAYIM)); ?></p>
            </div>
            <h1 class="h3 mb-3 font-weight-normal text-center">מחשבון אירועים עבריים</h1>
            <hr>
            <div id="message" class="text-center"></div>
            <div class="form">
                <div class="form-group">
                    <label for="date" class="text-info">תאריך לועזי</label>
                    <input type="date" id="date" placeholder="תאריך לועזי" required autofocus class="form-control datepicker">
                </div>
                
                <p>
                    <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#Advanced" aria-expanded="false" aria-controls="Advanced">מתקדם</button>
                </p>
                <div class="collapse" id="Advanced">
                    <div class="form-group">
                        <label for="name" class="text-info">של מי האירוע?</label>
                        <input type="text" id="name" placeholder="שם מלא" class="form-control datepicker">
                    </div>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" class="custom-control-input" id="night">
                        <label class="custom-control-label text-info float-right" for="night">אחרי השקיעה?</label>
                    </div>
                    <div class="form-group">
                        <br>
                        <label for="type" class="text-info">סוג האירוע</label>
                        <select class="custom-select" id="type">
                            <option disabled selected>לחץ כדי לראות את האפשרויות</option>
                            <option value="1">יום הולדת</option>
                            <option value="2">יום נישואין</option>
                            <option value="3">יארצייט</option>
                        </select>
                    </div>
                </div>
                
                <br>
                <button class="btn btn-lg btn-primary btn-block" id="submit">חשב</button> 
            </div>

            <div id="result" class="text-center"></div>       
        </div>

        <br><br>
        <footer class="font-small fixed-bottom bg-light">
            <div class="text-center py-3">
                &copy; 2020 כל הזכויות שמורות ל<a href="https://yehudae.net">יהודה אייזנברג</a>
            </div>
        </footer>
    </body>
</html>