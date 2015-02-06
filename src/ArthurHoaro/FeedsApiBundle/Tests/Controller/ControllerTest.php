<?php
/**
 * Created by PhpStorm.
 * User: ahoareau
 * Date: 05/02/2015
 * Time: 14:42
 */

namespace ArthurHoaro\FeedsApiBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase {
    protected function assertJsonResponse($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json')
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        if( $contentType !== false ) {
            $this->assertTrue(
                $response->headers->contains('Content-Type', $contentType),
                $response->headers
            );
        }

        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }

    protected function assertJsonResponseException($response, $statusCode = 200, $checkValidJson =  true, $contentType = 'application/json', $exceptionClass = null)
    {
        $this->assertJsonResponse($response, $statusCode, false, $contentType);
        if( $checkValidJson ) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );

            if( !empty($exceptionClass)) {
                $this->assertTrue($decode->error->exception[0]->class == $exceptionClass,
                    'Expected exception: '. $exceptionClass .' - Got '. $decode->error->exception[0]->class .' instead.');
            }
        }

    }
}