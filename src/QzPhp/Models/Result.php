<?php
namespace QzPhp\Models;

class Result{
    private $_messages = [];
    private $_success = true;
    private $_data = null;
    private $_stackTrace = null;

    public function messages(){
        return $this->_messages;
    }
    public function stackTrace(){
        return $this->_stackTrace;
    }
    public function data(){
        return $this->data;
    }
    public function isSuccess(){
        return $this->data;
    }

    public function toArray(){
        return [
            'messages' => $this->_messages,
            'stackTrace' => $this->_stackTrace,
            'data' => $this->_data,
            'success' => $this_success
        ];
    }

    /**
     * Overwrite messages with new value. Pass null to clear the value
     * @param  String[] $messages   new messages value
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function setMessage(array $messages = []){
        $this->_messages = $messages;

        return $this;
    }

    /**
     * Clear current message buffer
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function clearMessage(){
        $this->setMessage([]);

        return $this;
    }

    /**
     * Add new message
     * @param  String  $message   additional messages value
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function addMessage($message){
        $this->_messages[] = $message;
        return $this;
    }

    /**
     * Set success to false
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function error(){
        $this->_success = false;
        return $this;
    }

    /**
     * Set success to true
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function success(){
        $this->_success = true;
        return $this;
    }

    /**
     * Set success to value
     * @param  Bool  $success   success value
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function setSuccess($success){
        $this->_success = $success;
        return $this;
    }

    /**
     * Set stack trace / call stack
     * @param $stackTrace New stack trace value
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function setStackTrace($stackTrace){
        $this->_stackTrace = $stackTrace;
        return $this;
    }

    /**
     * Set data
     * @param $data New data value
     * @return QzPhp\Models\Result   current result object for chaining
     */
    public function setData($data){
        $this->data = $data;
        return $this;
    }
}
