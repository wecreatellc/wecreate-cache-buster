<?php

/**
 * The file that defines the cache buster class
 *
 * A class definition that includes attributes and functions for cache busting
 *
 * @link       http://www.onexnet.com
 * @since      1.0.0
 *
 * @package    WeCreate
 */

use MikeFunk\BustersPhp\BustersPhp;
use MikeFunk\BustersPhp\BustersPhpInterface;

/**
 * The asset cache-busting class.
 *
 * Provides attributes and functions for cache busting
 *
 * @since      1.0.0
 * @package    WeCreate
 * @author     Zach Lanich <zach@wecreatewebsites.net>
 */
class WeCreate_Cache_Buster extends BustersPhp implements BustersPhpInterface {

  /** Buster asset hashes */
  protected $busters = array();

  /**
   * Runs parent::__construct & reads busters.json file right away for easy access
   */
  public function __construct( array $config = array(), $fileSystem = null ) {

    parent::__construct( $config, $fileSystem );

    $this->readBusters();

  }

  /**
   * return css link tag
   *
   * @return string
   */
  public function css() {

    return $this->asset('css');

  }

  /**
   * return js script tag
   *
   * @return string
   */
  public function js() {

    return $this->asset('js');

  }

  /**
   * Reads busters.json file into internal array for easy access
   *
   * @return string
   * @throws LengthException
   * @throws UnderflowException
   */
  protected function readBusters() {

    // if no bustersJson, exception
    if ($this->fileSystem->fileExists($this->config['bustersJsonPath']) === false) {
      throw new LengthException('busters json not found.');
    }

    // get busters json and decode it
    $bustersJson = $this->fileSystem->getFile($this->config['bustersJsonPath']);
    if ($bustersJson == '') {
      throw new UnderflowException('busters json is empty.');
    }
    $busters = json_decode($bustersJson);

    $this->busters = (array) $busters;

    return $busters;

  }

  /**
   * return css and js
   *
   * @return string
   */
  public function assets() {

    return $this->asset('css')."\n".$this->asset('js');

  }

  /**
   * return hash by asset filename
   *
   * @param string $filename Full path relative to projects gulpfile.js
   *
   * @return string Hash
   */
  public function getHashByFilename( $filename ) {

    $modFileName = $this->pathRelativeToWpContent( $filename );

    if ( array_key_exists( $filename, $this->busters ) ) {
      return $this->busters[ $filename ];
    }
    else if ( array_key_exists( $modFileName, $this->busters ) ) {
      return $this->busters[ $modFileName ];
    }

    return false;
  }

  /**
   * Transform full path to path relative to wp-content (where gulpfile.js is)
   *
   * @param string $absPath Absolute path to asset
   *
   * @return string Relative path
   */
  public function pathRelativeToWpContent( $absPath ) {

    $wpc = trim( WP_CONTENT_DIR, '/' );
    $relPath = str_replace( $wpc, '', $absPath );
    $relPath = trim( $relPath, '/' );

    return $relPath;

  }

}
