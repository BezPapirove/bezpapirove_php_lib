<?php

namespace Tests\File;


use Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder;
use PHPUnit\Framework\TestCase;

final class PdfSignerTest extends TestCase
{

	private PdfSignCertificateAdder $pdfSigner;
	const SIGNED_PDF = '/home/kelso/bezpapirove/bezpapirove_php_lib/src/Pdf/sample.pdf';
	const CERT_PATH = '/home/kelso/bezpapirove/bezpapirove_php_lib/src/Pdf/certificate.pem';
	const HASH_DATA = 'your data';

//	protected function setUp(): void
//	{
//		parent::setUp();
//		$this->pdfSigner = new PdfSignCertificateAdder(
//			'../../src/Pdf/sample.pdf',
//			'src/Pdf/private-key.pem',
//			'src/Pdf/certificate.pem',
//			'your_certificate_password'
//		);
//	}

	public function testPdfSignCertificateAdder(): void
	{
		$inputPdfPath ='/home/kelso/bezpapirove/bezpapirove_php_lib/src/Pdf/sample.pdf';
		$privateKeyPath = '/home/kelso/bezpapirove/bezpapirove_php_lib/src/Pdf/private-key.pem';
		$certificatePath = '/home/kelso/bezpapirove/bezpapirove_php_lib/src/Pdf/certificate.pem';
		$password = 'aaaaaa';
//		$renamedPdfPath = '/home/kelso/bezpapirove/bezpapirove_php_lib/src/Pdf/m-sample.pdf';

//		try {
//			$pdf->setSourceFile($pdfFilePath);
//			// Perform actions on the PDF file as needed
//			// Example: Add a signature or modify the PDF content
//
//			// Assertions or other test logic
//			$this->assertTrue(true); // Example assertion
//		} catch (\Exception $e) {
//			$this->fail('Exception thrown: ' . $e->getMessage());
//		}

		// Assert if files exist and is readable
//		$this->assertFileExists($inputPdfPath);
//		$this->assertFileExists($certificatedPdfPath);
//		$this->assertFileIsReadable($inputPdfPath);
//		$this->assertFileExists($privateKeyPath);
//		$this->assertFileIsReadable($privateKeyPath);
//		$this->assertFileExists($certificatePath);
//		$this->assertFileIsReadable($certificatePath);

//		// Instantiate the PdfSignCertificateAdder
//		$pdfSigner = new \Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder(
//			$inputPdfPath,
//			$privateKeyPath,
//			$certificatePath,
//			$password
//		);

// Instantiate the PdfSignCertificateAdder
		$pdfSigner = new \Bezpapirove\BezpapirovePhpLib\Pdf\PdfSignCertificateAdder(
			$inputPdfPath,
			$privateKeyPath,
			$certificatePath,
			$password
		);

// Assert if signed pdf exists
		$this->assertFileExists($inputPdfPath);

// Rename signed pdf
		$renamedPdfPath = 'm-'.$inputPdfPath;

// There might be a situation where the signed PDF already exists,
// it's generally a good practice to delete the file if it already exists.
//		if (file_exists($renamedPdfPath)) {
//			unlink($renamedPdfPath);
//		}

// Add certificate to signed PDF
//		try {
//			$pdfSigner->addCertificateToSignedPdf($signedPdfPath, $certificatePath, hash('sha256', self::HASH_DATA));
//			$renamedPdfPathExists = file_exists($renamedPdfPath);
//			$this->assertTrue($renamedPdfPathExists, 'Renamed PDF does not exist after adding certificate');
//		} catch (\Exception $e) {
//			$this->fail('Exception thrown while adding certificate to PDF: '.$e->getMessage());
//		}

// Assert the signed pdf file exists
		$this->assertFileExists($inputPdfPath);

// Assert the signed pdf is not empty
//		$content = file_get_contents($signedPdfPath);
//		$this->assertNotEmpty($content);

		// Add certificate to signed PDF
//		$pdfSigner->addCertificateToSignedPdf($signedPdfPath, $certificatePath, hash('sha256', $hashData));

		// Assert the signed pdf file exists
		$this->assertFileExists($renamedPdfPath);

		// Assert the signed pdf is not empty
		$content = file_get_contents($renamedPdfPath);
		$this->assertNotEmpty($content);
	}

//	public function testAddCertificateToSignedPdf(): void
//	{
//		$hash = hash('sha256', self::HASH_DATA);
//		try {
//			$this->pdfSigner->addCertificateToSignedPdf(self::SIGNED_PDF, self::CERT_PATH, $hash);
//			$this->assertFileExists(self::SIGNED_PDF);
//			$content = file_get_contents(self::SIGNED_PDF);
//			$this->assertNotEmpty($content);
//			$fileSize = filesize(self::SIGNED_PDF);
//			$this->assertGreaterThan(0, $fileSize, 'Signed PDF file size should be greater than 0');
//			$fileType = mime_content_type(self::SIGNED_PDF);
//			$this->assertEquals('application/pdf', $fileType, 'Signed PDF file should be of type PDF');
//		} catch (\Exception $e) {
//			$this->fail('Unexpected exception thrown: '.$e->getMessage());
//		}
//	}

	public function tearDown(): void
	{
		if (file_exists(self::SIGNED_PDF)) {
			unlink(self::SIGNED_PDF);
		}
		parent::tearDown();
	}


}
