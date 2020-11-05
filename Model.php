<?php
namespace SocketServer;

class Model {
    /**
     * Pings IP $ip and returns the average ping time
     * The function is tested on macOS!!!
     */
    static public function pingAvg($ip) 
    {
        $pingresult = exec("ping -c 5 $ip", $results, $status);
        if (0 !== $status || !is_array($results)) {
            // The server is not responding
            return "The server $ip is not responding or bad response!";
        } 
        
        // The server is responding
        foreach ($results as $result) {
            if (strpos($result, 'round-trip') !== 0) { continue; }
            
            $parts = explode(' ', $result);
            
            return explode('/', $parts[3])[1] . ' ' . $parts[4];
        }
        
        return "The server $ip, couldn't get the Avg ping\n* The function was tested on macOS\n";
    }
    
    /**
     * REturns the total disk space on server
     */
    static public function totalDiskSpace() 
    {
        return self::formatBytes(disk_total_space("/"));
    }
    
    static public function formatBytes($bytes) {
        $symbols = array('B', 'KiB', 'MiB', 'GiB', 'TiB', 'PiB', 'EiB', 'ZiB', 'YiB');
        $exp = $bytes ? floor(log($bytes) / log(1024)) : 0;

        return sprintf('%.2f '.$symbols[$exp], ($bytes/pow(1024, floor($exp))));
    }
    
    static public function googleSearch($phrase)
    {
        echo "Phrase: $phrase";
        try {
            $url = "https://www.google.com/search?num=5&q=" . urlencode($phrase);

            echo "URL: $url\n";

            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_REFERER, 'https://www.google.com');

            $result = curl_exec($curl);
            curl_close($curl);

            $regx = '/https?:\/\/.*?"/';
            $num = preg_match_all($regx, $result, $matches);
            
            print_r($matches);
            
            $res = "\n";
            
            if (is_array($matches[0])) {
                $count = 1;
                foreach ($matches[0] as $matche) {
                    if ($count > 5) break;
                    $res .= $count . ". " . trim($matche, '"') . "\n\n";
                    $count++;
                }
            }
            
            return $res ;
        } catch (Exception $ex) {
            echo $ex;
        }
    }
}