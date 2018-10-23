<?php
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="asset/css/bulma.min.css">
    <link rel="stylesheet" type="text/css" href="asset/font-awesome/css/all.min.css">
    <link rel="stylesheet" href="asset/github-buttons/assets/css/main.css">
    <title><?php echo Registry::NAME; ?></title>
</head>
<body>
    <div class="container is-fluid">

        <section class="hero">
          <div class="hero-body">
            <div class="container">              
                <div class="columns">
                    <div class="column">
                        <i class="fa fa-globe" style="font-size:48px;"></i>
                    </div>
                    <div class="column is-full">
                        <h1 class="title" style="line-height: 1.2;">
                        <?php echo Registry::NAME; ?>
                        </h1>         
                    </div>
                </div>
              <h2 class="subtitle">
                  <?php echo Registry::DESCRIPTION; ?>
              </h2>
            </div>
          </div>
        </section>    

        <div class="columns" style="min-height: 600px;">
            <div class="column"></div>
            <div class="column is-four-fifths">
                <nav class="level">
                  <div class="level-item">
                    <div>
                        <p>Supports JavaScript generated contents.</p>
                        <p>Type a target URL below and press <code>Go</code>.</p>
                    </div>
                  </div>
                </nav>  
                <form method="get">
                    <div class="field has-addons">
                        <p class="control is-expanded has-icons-left">
                            <label>                               
                                <input class="is-medium input" required="required" type="url" name="url" placeholder="Type a URL." />
                                <span class="icon is-small is-left">
                                  <i class="fa fa-arrow-right"></i>
                                </span>          
                            </label>
                        </p>        
                        <p class="control">
                            <label>                               
                                <button class="is-medium button is-primary is-info">Go</button>                                
                            </label>                            
                        </p>                        
                    </div>
                    <p>
                        <label for="html" class="radio">
                            <input id="html" type="radio" name="output" checked="checked" />
                            HTML
                        </label>
                        <label for="json" class="radio">
                            <input id="json" type="radio" name="output" value="json" />
                            JSON
                        </label>
                        <label for="screenshot" class="radio">
                            <input id="screenshot" type="radio" name="output" value="screenshot" />
                            Screenshot
                        </label>
                    </p>
                </form>        
            </div>
            <div class="column"></div>
        </div>  

        <footer class="footer">

            <div class="columns is-mobile">
              <div class="column is-4 is-offset-8">
                  <!-- Github Buttons -->
                  <a class="github-button" href="https://github.com/michaeluno" data-size="large" aria-label="Follow @michaeluno on GitHub">Follow @michaeluno</a>
                  <a class="github-button" href="https://github.com/michaeluno/php-simple-web-scraper" data-size="large" data-show-count="true" aria-label="Star michaeluno/php-simple-web-scraper on GitHub">Star</a>
                  <a class="github-button" href="https://github.com/michaeluno/php-simple-web-scraper/fork" data-size="large" data-show-count="true" aria-label="Fork michaeluno/php-simple-web-scraper on GitHub">Fork</a>
              </div>
            </div>
            <div class="content has-text-centered">
                <p>
                    <?php
                    echo '<a href="' . Registry::PROGRAM_URI . '" target="_blank" title="' . Registry::DESCRIPTION . '">'
                        . Registry::NAME . ' ' . Registry::VERSION
                        . '</a>&nbsp;';
                    echo sprintf(
                        Utility::getCopyRight( '%1$s', 2018 ),
                        '<a href="' . Registry::AUTHOR_URI . '" target="_blank">' . Registry::AUTHOR . "</a>"
                    ); ?>
                </p>
            </div>

        </footer>           
    </div><!-- .container -->
    <script async defer src="asset/github-buttons/buttons.js"></script>
</body>
</html>
