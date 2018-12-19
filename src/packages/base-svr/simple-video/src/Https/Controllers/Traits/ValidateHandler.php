<?php
namespace Basesvr\SimpleVideo\Https\Controllers\Traits;

use Validator;

/**
 * Trait ValidateHandler
 * @package App\Http\Controllers\Traits
 */
trait ValidateHandler
{
    /**
     * 验证字段
     *
     * @param array $requestData
     * @param array $rule
     * @param array $msg
     * @param bool $isFirst
     *
     * @return array
     */
    public function validateField(array $requestData, array $rule, array $msg = [], $isFirst = false)
    {
        $validator = Validator::make($requestData, $rule, $msg);

        if ($validator->fails()) {
            $messages = $validator->messages()->toArray();
            $errorMsg = [];
            if (count($messages) > 0) {
                foreach ($messages as $key => $value) {
                    $errorMsg[$key] = [
                        '$valid'      => false,
                        '$invalid'    => true,
                        '$srverror'   => true,
                        '$srvmessage' => $value[0],
                    ];
                    if ($isFirst) {
                        break;
                    }
                }

                return $errorMsg;
            }
        }

        return [];
    }
}