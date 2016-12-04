<?php

namespace ArthurHoaro\RssCruncherApiBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;

class ControllerTest extends WebTestCase {

    /**
     * @param $response
     * @param int $statusCode
     * @param bool $checkValidJson
     * @param string $contentType
     */
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

    /**
     * @param $response
     * @param int $statusCode
     * @param bool $checkValidJson
     * @param string $contentType
     * @param null $exceptionClass
     */
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