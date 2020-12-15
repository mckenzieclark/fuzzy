<?php
/**
 * Fuzzy plugin for Craft CMS 3.x
 *
 * Fuzzy dates and events
 *
 * @link      https://github.com/mckenzieclark
 * @copyright Copyright (c) 2020 John Clark
 */

namespace mckenzieclark\fuzzy\services;

use mckenzieclark\fuzzy\Fuzzy;

use \DateTime;
use Craft;
use craft\base\Component;

/**
 * @author    John Clark
 * @package   Fuzzy
 * @since     1.0.0
 */
class FuzzyDate extends Component
{
    // Public Methods
    // =========================================================================

    /*
     * @return mixed
     */
    public function exampleService()
    {
        $result = 'something';
        // Check our Plugin's settings for `someAttribute`
        if (Fuzzy::$plugin->getSettings()->someAttribute) {
        }

        return $result;
    }


    public function date($date, $format, $prefix)
    {
      $output = '';
      $format = $this->_reformat($date, $format, $prefix);
      if(!preg_match('/\S/', $format)) return false;

      $newDate = $this->_getDateTime($date);

      if($prefix && $this->_missing($date)) {
        $output.= "$prefix ";
      } 
      $output.= $newDate->format($format);
      return $output;
    }


    public function timestamp($date)
    {
      $newDate = $this->_getDateTime($date);
      return $newDate->format('U');
    }


    public function ago($date)
    {
      $newDate = $this->_getDateTime($date);
      return $this->_time_elapsed_string($newDate->format('Y-m-d'));
    }


    // Private Methods
    // =========================================================================

    private function _missing($date)
    {
      return empty($date->day) || empty($date->month) || empty($date->year);
    }

    private function _reformat($date, $format, $round)
    {
      if((empty($date->month) || empty($date->day)) && $round) {
        $format = preg_replace('/[djDlSz]/', '', $format);
      }

      if(empty($date->month) && $round) {
        $format = preg_replace('/[FMmn]/', '', $format);
      }

      if(empty($date->year)) {
        $format = preg_replace('/[Yy]/', '', $format);
      }

      $format = trim(preg_replace('/^\s,|\s,\s|,\s$/', ' ', $format));
      return $format;
    }

    private function _getDateTime($date)
    {
      $day = empty($date->day) ? 1 : $date->day;
      $month = $this->_getMonthName(empty($date->month) ? 1 : $date->month);
      $year = empty($date->year) ? date('Y') : $date->year;
      $newDate = DateTime::createFromFormat("d M Y H:i", "$day $month $year 12:00");
      return $newDate;      
    }

    private function _getMonthName($number)
    {
      $dateObj   = DateTime::createFromFormat('!m', $number);
      $monthName = $dateObj->format('F');
      return $monthName;
    }

    private function _time_elapsed_string($datetime, $full = false) {
      $now = new DateTime;
      $ago = new DateTime($datetime);
      $diff = $now->diff($ago);

      $diff->w = floor($diff->d / 7);
      $diff->d -= $diff->w * 7;

      $string = array(
          'y' => 'year',
          'm' => 'month',
          'w' => 'week',
          'd' => 'day',
          'h' => 'hour',
          'i' => 'minute',
          's' => 'second',
      );
      foreach ($string as $k => &$v) {
          if ($diff->$k) {
              $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
          } else {
              unset($string[$k]);
          }
      }

      if (!$full) $string = array_slice($string, 0, 1);
      return $string ? implode(', ', $string) . ' ago' : 'just now';
    }
}
