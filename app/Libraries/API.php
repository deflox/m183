<?php

/**
 * Library for creating API responses.
 *
 * @author Leo Rudin
 */

namespace App\Libraries;

use App\Accessor;

class API extends Accessor
{
    /**
     * Creates a general json response.
     *
     * @param  $res
     * @param  $content
     * @return string
     */
    public function createResponse($res, $content = null)
    {
        if (isset($content)) {
            $response = [
                'errors' => false,
                'content' => $content,
            ];
        } else {
            $response = [
                'errors' => false,
            ];
        }
        return $res->withJson($response, 200, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }

    /**
     * Creates a json error response.
     *
     * @param  $res
     * @param  $message
     * @param  null $messages
     * @return string
     */
    public function createErrorResponse($res, $message, $messages = null)
    {
        if (isset($messages) && !empty($messages)) {
            $response = [
                'errors' => true,
                'message' => $message,
                'messages' => $messages,
            ];
        } else {
            $response = [
                'errors' => true,
                'message' => $message,
            ];
        }
        return $res->withJson($response, 200, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP);
    }
}