<?php
    
    
    /**
     * For a command line argument of -h, give some instructions on how to run the tool
     */
    function printHelp()
    {
        echo
        "\nWelcome to the Stadium Goods web parsing tool!
Here you can pass a URL (or multiple separated by spaces) and will be given a csv of the products.
For example, running

php index.php https://www.stadiumgoods.com/adidas 

will return a list of the Product Name and Price of each product on each page with a header followed by each product in the format:

`Product Name`,`Price`
`Air Jordan 1`, `$940.00`
`Air Jodan 6 Retro`, `300.00`


The parser will continue to cycle through pages until the list is exhaused
...\n\n";
    }
    
    
    /**
     * Standard curl, get the data from the site and load into DOM parser
     *
     * @param $url a valid url
     * @return dom parsed for body of site
     */
    function curlToDOM($url) {
        $ci = curl_init($url);
        curl_setopt($ci, CURLOPT_HEADER, 0);
        curl_setopt($ci, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ci, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ci, CURLOPT_FOLLOWLOCATION, 1);
        $dom = new simple_html_dom();
        $siteData = $dom->load(curl_exec($ci));
        curl_close($ci);
        return $siteData;
    }