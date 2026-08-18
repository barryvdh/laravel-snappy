<?php

namespace Barryvdh\Snappy\Tests;

use Barryvdh\Snappy\Facade;
use Barryvdh\Snappy\Facades\SnappyPdf;
use Barryvdh\Snappy\PdfWrapper;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PdfTest extends TestCase
{
    public function testAlias(): void
    {
        $pdf = \PDF::loadHtml('<h1>Test</h1>');
        /** @var Response $response */
        $response = $pdf->download('test.pdf');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="test.pdf"', $response->headers->get('Content-Disposition'));
    }

    public function testFacade(): void
    {
        $pdf = SnappyPdf::loadHtml('<h1>Test</h1>');
        /** @var Response $response */
        $response = $pdf->download('test.pdf');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="test.pdf"', $response->headers->get('Content-Disposition'));
    }

    public function testDownload(): void
    {
        $pdf = SnappyPdf::loadHtml('<h1>Test</h1>');
        /** @var Response $response */
        $response = $pdf->download('test.pdf');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="test.pdf"', $response->headers->get('Content-Disposition'));
    }

    public function testStream(): void
    {
        $pdf = SnappyPdf::loadHtml('<h1>Test</h1>');
        /** @var Response $response */
        $response = $pdf->stream('test.pdf');

        $this->assertInstanceOf(StreamedResponse::class, $response);
        $this->assertFalse($response->getContent());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('inline; filename="test.pdf"', $response->headers->get('Content-Disposition'));
    }

    public function testInline(): void
    {
        $pdf = SnappyPdf::loadHtml('<h1>Test</h1>');
        /** @var Response $response */
        $response = $pdf->inline('test.pdf');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('inline; filename="test.pdf"', $response->headers->get('Content-Disposition'));
    }

    public function testView(): void
    {
        $pdf = SnappyPdf::loadView('test');
        /** @var Response $response */
        $response = $pdf->download('test.pdf');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertNotEmpty($response->getContent());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('attachment; filename="test.pdf"', $response->headers->get('Content-Disposition'));
    }

    public function testOutputRemovesTemporaryFiles(): void
    {
        $temporaryFolder = sys_get_temp_dir() . '/snappy-test-' . uniqid();
        $this->assertTrue(mkdir($temporaryFolder));

        try {
            $pdf = SnappyPdf::loadHtml('<h1>Test</h1>');
            $this->assertInstanceOf(PdfWrapper::class, $pdf->setTemporaryFolder($temporaryFolder));

            $this->assertNotEmpty($pdf->output());
            $this->assertSame(
                [],
                glob($temporaryFolder . '/knp_snappy*'),
                'Temporary files should be removed after output().'
            );
        } finally {
            $this->assertTrue(rmdir($temporaryFolder));
        }
    }

    public function testSaveRemovesTemporaryFiles(): void
    {
        $temporaryFolder = sys_get_temp_dir() . '/snappy-test-' . uniqid();
        $this->assertTrue(mkdir($temporaryFolder));
        $filename = $temporaryFolder . '/test.pdf';

        try {
            $pdf = SnappyPdf::loadHtml('<h1>Test</h1>');
            $this->assertInstanceOf(PdfWrapper::class, $pdf->setTemporaryFolder($temporaryFolder));
            $this->assertInstanceOf(PdfWrapper::class, $pdf->save($filename));

            $this->assertFileExists($filename);
            $this->assertSame(
                [],
                glob($temporaryFolder . '/knp_snappy*'),
                'Temporary files should be removed after save().'
            );
            $this->assertTrue(unlink($filename));
        } finally {
            $this->assertTrue(rmdir($temporaryFolder));
        }
    }

    public function testOutputRemovesTemporaryFilesWhenGenerationFails(): void
    {
        $temporaryFolder = sys_get_temp_dir() . '/snappy-test-' . uniqid();
        $this->assertTrue(mkdir($temporaryFolder));

        try {
            $pdf = SnappyPdf::loadHtml('<h1>Test</h1>');
            $this->assertInstanceOf(PdfWrapper::class, $pdf->setTemporaryFolder($temporaryFolder));
            // Force generation to fail so we can prove the finally block still cleans up.
            $pdf->snappy()->setBinary($temporaryFolder . '/does-not-exist');

            $thrown = false;
            try {
                $pdf->output();
            } catch (\RuntimeException $e) {
                $thrown = true;
            }

            $this->assertTrue($thrown, 'output() should throw when the binary is invalid.');
            $this->assertSame(
                [],
                glob($temporaryFolder . '/knp_snappy*'),
                'Temporary files should be removed even when output() fails.'
            );
        } finally {
            $this->assertTrue(rmdir($temporaryFolder));
        }
    }

}
