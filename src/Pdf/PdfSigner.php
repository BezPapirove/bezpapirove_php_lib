<?php

namespace Bezpapirove\BezpapirovePhpLib\Pdf;

use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfParser\PdfParserException;
use TCPDF;

class PdfSigner
{
	private TCPDF $pdf;
	private string $signatureImage;
	private string $outputFile;
	private string $privateKeyPath;
	private string $certificate;
	private string $password;

	const HASH_ALGO = 'sha256';
	const SIGN_FIELD_NAME = 'signature_field_name';
	const SIGN_ALGO = OPENSSL_ALGO_SHA256;
	private Fpdi $fpdi;

	/**
	 * PdfSigner constructor.
	 *
	 * @param string $pdfFile
	 * @param string $signatureImage
	 * @param string $outputFile
	 * @param string $privateKeyPath
	 * @param string $certificate
	 * @param string $password
	 *
	 * @throws PdfParserException
	 */
	public function __construct(string $pdfFile, string $signatureImage, string $outputFile, string $privateKeyPath, string $certificate, string $password)
	{
		$this->pdf = new TCPDF();
		$this->fpdi = new Fpdi();
		$this->signatureImage = $signatureImage;
		$this->outputFile = $outputFile;
		$this->privateKeyPath = $privateKeyPath;
		$this->certificate = $certificate;
		$this->password = $password;
		$this->fpdi->setSourceFile($pdfFile);
	}

	/**
	 * Sign the PDF document.
	 *
	 */
	public function signPdf(): void
	{
		$this->addPageToPdf();
		$this->addSignatureFieldToPdf();
		$this->outputSignedPdf();
	}

	/**
	 * Add a page to the PDF.
	 */
	private function addPageToPdf(): void
	{
		$this->pdf->AddPage();
	}

	/**
	 * Add signature field to the PDF.
	 */
	private function addSignatureFieldToPdf(): void
	{
		$this->pdf->Image($this->signatureImage, 10, 10, 30, 30); // Example image placement

		$this->pdf->SetFont('helvetica', '', 12);
		$this->pdf->SetTextColor(0, 0, 0);

		// Add signature field (just an example, adjust as per your need)
		$this->pdf->Text(10, 50, 'Signature Field');

		// Example of setting a signature, adjust as per your need
		$this->pdf->setSignature(self::SIGN_FIELD_NAME);
	}

	/**
	 * Output the signed PDF.
	 */
	private function outputSignedPdf(): void
	{
		$this->pdf->Output($this->outputFile, 'F');
	}

	/**
	 * Sign a hash of the PDF.
	 *
	 * @param string $hash
	 *
	 */
	private function signPdfHash(string $hash): void
	{
		$privateKey = openssl_pkey_get_private(file_get_contents($this->privateKeyPath));
		openssl_sign($hash, $signature, $privateKey, self::SIGN_ALGO);
		unset($privateKey);

		$this->pdf->setSignature($signature, $this->certificate, $this->password);
	}

	/**
	 * Sign the PDF using imported pages.
	 *
	 */
	public function sign(): void
	{
		// Import all pages
		$pageCount = $this->fpdi->setSourceFile($this->pdf);
		for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
			if (!empty($this)) {
				$tplId = $this->fpdi->importPage($pageNo);
			}
			$this->pdf->AddPage();
			$this->pdf->useTemplate($tplId);

			if ($pageNo == $pageCount) {
				// Add signature on the last page
				$this->addSignature();
			}
		}

		$this->outputSignedPdf();
	}

	/**
	 * Add the signature image to the last page.
	 */
	private function addSignature(): void
	{
		// Position of the signature
		$x = 150;
		$y = 250;
		$width = 50;

		$this->pdf->Image($this->signatureImage, $x, $y, $width);
	}
}

// Example usage:
$pdfFile = 'path/to/your/input.pdf';
$signatureImage = 'path/to/your/signature.png';
$outputFile = 'path/to/your/signed_output.pdf';
$privateKeyPath = 'path/to/your/private-key.pem';
$certificate = 'path/to/your/certificate.pem';
$password = 'your_certificate_password';

	$signer = new PdfSigner($pdfFile, $signatureImage, $outputFile, $privateKeyPath, $certificate, $password);
	$signer->sign();

