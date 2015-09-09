<?PHP

/**
 *  ~ Tables Manager v1.0.4.29 ~
 * Made for easier manipulation with messy HTML tables
 * 
 * @license https://github.com/xZero707/PHP-Tables-Manager/blob/master/LICENSE <Apache License version 2.0>
 * @author xZero707 <https://github.com/xZero707/>
 * @website https://www.elite7hackers.net/
 */

class tablesman {

    public $GlobalDelimiter = false;
    public $generator = "\n-- Generated by Tables Manager\n-- Version: 1.0.4.23\n-- Author: xZero\n-- https://www.elite7hackers.net/\n";
    public $outputTyp = "direct";
    protected $linesArr = array();
    public $output = array();
    public $isNewInst = true;

    function __construct() {
        $this->setGeneratedBy($this->generator);
    }

    public function setGlobalDelimiter($val) {
        $this->GlobalDelimiter = $val;
    }

    public function setGeneratedBy($val) {
        if ($val) {
            $this->generator = "<!-- {$val} -->\n";
        } else {
            $this->generator = '';
        }
    }

    public function setOutput($val) {
        $this->outputTyp = $val;
    }

    public function create($tablename, $identifier = 'class', $code = NULL) {
        $this->FlushOutput("\n{$this->generator}<table {$identifier}='{$tablename}' {$code}>\n");
    }

    public function header($input, $dlm = '*', $code = NULL) {
        if ($this->GlobalDelimiter) {
            $dlm = $this->GlobalDelimiter;
        }
        if (!is_array($input)) {
            $headers = \explode($dlm, $input);
        } else {
            $headers = $input;
        }
        $this->linesArr[] = "<tr$code>";
        foreach ($headers as $header) {
            $this->linesArr[] = "<th>$header</th>";
        }
        $this->linesArr[] = "</tr>";
        $this->FlushOutput(implode("\n", $this->linesArr));
    }

    public function row($input, $dlm = '*', $code = NULL) {
        if ($this->GlobalDelimiter) {
            $dlm = $this->GlobalDelimiter;
        }
        if (!is_array($input)) {
            /* @var $columns type */
            $columns = \explode($dlm, $input);
        } else {
            $columns = $input;
        }
        $this->linesArr[] = " <tr$code>";
        foreach ($columns as $column) {
            $this->linesArr[] = "<td>$column</td>";
        }
        $this->linesArr[] = "</tr>";
        $this->FlushOutput(implode("\n", $this->linesArr));
    }

    public function footer($input, $dlm = '*', $code = NULL) {
        if ($this->GlobalDelimiter) {
            $dlm = $this->GlobalDelimiter;
        }
        if (!is_array($input)) {
            /* @var $input type */
            $tfoot = \explode($dlm, $input);
        } else {
            $tfoot = $input;
        }
        $this->linesArr[] = "<tfoot>\n<tr $code>";
        /* @var $tfoot type */
        foreach ($tfoot as $column) {
            $this->linesArr[] = "<td>$column</td>";
        }
        $this->linesArr[] = "</tr>\n</tfoot>";
        $this->FlushOutput(implode("\n", $this->linesArr));
    }

    public function close() {
        $this->FlushOutput("</table>\n{$this->generator}\n");
        $this->isNewInst = true;
    }

    private function FlushOutput($out) {
        $this->linesArr = array(); // Clean array
        switch ($this->outputTyp) {
            case "direct":
                echo $out;
                break;

            case "array":
                if ($this->isNewInst) {
                    $this->output = array(); // Reset output buffer if run in new instance
                    $this->isNewInst = false; // Set to false so there will be no more buffer resets until new instance
                }
                $this->output[] = $out;
                break;

            default:
                echo $out;
                break;
        }
    }

}

if (defined("TBM_INIT")) {
    ${TBM_INIT} = new tablesman; // Create instance tablesman
}