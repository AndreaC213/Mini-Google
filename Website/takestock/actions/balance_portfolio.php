<?php

    include('../config.php');
    include('include/check_session.php');
    include('include/db.php');
    include('include/portfolio.php');
    include 'include/stockclient.php';

    // Make sure they passed a valid portfolio
    if (! isset($_GET['pid'])) {
        die("No portfolio specified");
    }
    $pid = $_GET['pid'];
    $portfolio = new Portfolio($conn);
    $portfolio->load($pid);
    if (! $portfolio->isOwner($email)) {
        die("Invalid portfolio");
    }

    // Start up the stock client for fetching prices
    $client = new StockClient($stockserver_address, $stockserver_port);

    // Set up the filenames
    $t = time();
    $input_file = "$uploads_path/input-$t.csv";
    $output_file = "$uploads_path/output-$t.csv";

    // Dump the portfolio to a CSV file for R's input
    $t = time();
    $file = fopen($input_file, "w");
    fputcsv($file, array('symbol', 'shares', 'price'));
    foreach ($portfolio->getStocks() as $stock) {
        $symbol = $stock['symbol'];
        $shares = $stock['shares'];
        $price = $client->getQuoteUSD($symbol);
        fputcsv($file, array($symbol, $shares, $price));
    }
    fclose($file);

    // Run the test script, first arg is input, second arg is output
    $output = shell_exec("$runner_path/Runner.sh $runner_path/scripts/balance.R $input_file $output_file 2>&1");

    $title = "Balance Portfolio";
    include 'templates/dialog_top.php';
?>

<form method="post"
      action='<?php echo "$basedir/actions/perform_actions.php"; ?>'
      id="form">
    <input type="hidden" name="pid" value="<?php echo $pid; ?>">
    <div class="mdl-card__supporting-text">
        <div class="mdl-grid">
            The following actions will be taken:
        </div>
        <?php
            $file = fopen($output_file, 'r');
            $row = fgetcsv($file);
            while (($row = fgetcsv($file)) !== FALSE) {
                echo "<div class='mdl-grid'>$row[0] $row[2] $row[1]</div>";
                echo "<input type='hidden' name='actions[]' value='$row[0]'>";
                echo "<input type='hidden' name='symbols[]' value='$row[1]'>";
                echo "<input type='hidden' name='amounts[]' value='$row[2]'>";
            }
            fclose($file);
        ?> 
        <script>
            function toggle(id) {
                e = document.getElementById(id);
                if(e.style.display == 'block')
                    e.style.display = 'none';
                else
                    e.style.display = 'block';
            }
        </script>
        <div class="mdl-grid">
            <a href="#"
               onclick="toggle('output')">
                <small>Click to Toggle Output</small>
            </a>
        </div>
        <div class="mdl-grid" id="output" style="display:none;">
            <?php echo "<pre>$output</pre>" ?>
        </div>
        <center>
            <div class="mdl-card__actions">
                <button class="mdl-button
                               mdl-js-button
                               mdl-js-ripple-effect
                               mdl-button--raised
                               mdl-button--colored"
                        type="submit">
                    Confirm
                </button>
            </div>
        </center>
</form>

<?php include 'templates/dialog_bottom.php'; ?>
