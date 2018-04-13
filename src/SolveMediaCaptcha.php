<?php
/**
 * TraderInteractive\SolveMedia\Service class for accessing the Solve Media API service.
 * This component has been heavily modified from it's original form to
 * encapsulate the functionality in a class based structure that is
 * compatible with class autoloading functionality.
 *
 * @author Chris Ryan <christopher.ryan@dominionenterprises.com>
 */
namespace Rubensrocha\SolveMediaCaptcha;

use Exception;
use Config;

final class SolveMediaCaptcha
{
    /**
     * The solvemedia server URL's
     */
    const ADCOPY_API_SERVER = 'http://api.solvemedia.com';
    const ADCOPY_API_SECURE_SERVER = 'https://api-secure.solvemedia.com';
    const ADCOPY_SIGNUP = 'http://api.solvemedia.com/public/signup';
    /**
     * @var ClientInterface
     */
    protected $_client;
    /**
     * @var string
     */
    protected $ckey;
    /**
     * @var string
     */
    protected $vkey;
    /**
     * @var string
     */
    protected $hkey;
    /**
     * @var string
     */
    protected $usessl;
    /**
     * Construct a Service object with the required api key values.
     *
     * @param string $ckey A public key for solvemedia
     * @param string $vkey A private key for solvemedia
     * @param string $hkey An optional hash key for verification
     * @throws Exception
     */
    public function __construct($ckey,  $vkey,  $hkey, $usessl)
    {
        if (empty($ckey) || empty($vkey)) {
            throw new Exception('To use solvemedia you must get an API key from ' . self::ADCOPY_SIGNUP);
        }
        
        $this->ckey = Config::get('solvemediacaptcha.ckey');
        $this->vkey = Config::get('solvemediacaptcha.vkey');
        $this->hkey = Config::get('solvemediacaptcha.hkey');
        $this->usessl = Config::get('solvemediacaptcha.ssl');
    }
    /**
     * Gets the challenge HTML (javascript and non-javascript version).
     * This is called from the browser, and the resulting solvemedia HTML widget
     * is embedded within the HTML form it was called from.
     *
     * @param string $error The error given by solvemedia (optional, default is null)
     * @param boolean $useSsl Should the request be made over ssl? (optional, default is false)
     * @return string The HTML to be embedded in the user's form.
     */
    public function display( $error = null)
    {
		$useSsl = $this->usessl;
        $server = $useSsl ? self::ADCOPY_API_SECURE_SERVER : self::ADCOPY_API_SERVER;
        $errorpart = $error ? ';error=1' : '';
        return <<<EOS
<script type="text/javascript" src="{$server}/papi/challenge.script?k={$this->ckey}{$errorpart}"></script>
<noscript>
    NO JAVASCRIPT
</noscript>
EOS;
    }
    /**
     * Calls an HTTP POST function to verify if the user's guess was correct
     *
     * @param string $remoteip
     * @param string $challenge
     * @param string $response
     * @return bool
     */
    public function checkAnswer( $remoteip,  $challenge = null,  $response = null)
    {
		if (empty($remoteip)) {
            return FALSE;
        }
        //discard spam submissions
        if (empty($challenge) || empty($response)) {
            return FALSE;
        }
		
		include_once('solvemedialib.php');
		$req = solvemedia_check_answer($this->vkey, $remoteip, $challenge, $response);
		//dd($req);
		if(!$req->is_valid){
			return FALSE;
		}else{
			return TRUE;
		}		
        
    }
}