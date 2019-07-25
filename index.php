<?php
    
    include_once "simple_html_dom.php";
    include_once 'helpers.php';
    
   
    const PAGE_SIZE = 96;
    
    
    if ($argv)
    {
        
        $url = "";
        foreach($argv as $key=>$value)
        {
            if (strcasecmp($value, "-h")===0)
            {
                printHelp();
            }
            else if (filter_var($value, FILTER_VALIDATE_URL))
            {
                $url = $value;
            }
            else if ($value!="index.php")
            {
                echo "\nValue: $value passed is not a valid url, please try again.\n";
            }
        }
        
        
        $data = curlToDOM($url);
        $pageCt=0;
        
        
        //get the total number of pages for a filter
        foreach($data->find('div.toolbar > div.category-count > h5') as $r)
        {
            $rangeHeader = $r->innertext;
            //the total is enclosed in parents, strip that out
            $begin = strpos($rangeHeader, "(") + 1;
            $end = strpos($rangeHeader, ")") - 1;
            $totalStr = substr($rangeHeader, $begin, intval($end) - intval($begin) + 1);
            $total = intval($totalStr);
    
            //divide by the pageCt to an integer to get the number of pages
            $pageCt = floor($total / PAGE_SIZE);
            $pageCt = $pageCt + (($total % PAGE_SIZE == 0) ? 0 : 1);
    
        }
        
        $products = array();
        $price = array();
        
        
        
        //cycle through each page, getting the shoes for each page
        for ($i = 1; $i <= $pageCt; $i++)
        {
            //only print header if we have enter the for loop
            if ($i==1)
            {
                echo "Product Name, Price\n";
            }
            
            $composedUrl = $url . "/page/$i";
            $data = curlToDOM($composedUrl);
    
            foreach ($data->find('.product-info > .product-name > a') as $item)
            {
        
                $products[] = $item->title;
            }
    
            foreach ($data->find('.product-info > .price-box > .regular-price > .price') as $item)
            {
                $price[] = $item->innertext;
            }
            
            //output them
            $len = sizeof($products);
            for ($j = 0; $j < $len; $j++)
            {
                echo $products[$j].",".$price[$j]."\n";
            }
    
        }
            
    }
    