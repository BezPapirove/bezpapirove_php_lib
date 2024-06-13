<?php

namespace Bezpapirove\BezpapirovePhpLib\Pdf;

use setasign\Fpdi\Tcpdf;
use setasign\Fpdi\PdfParser\PdfParserException;
use setasign\Fpdi\Tcpdf\Fpdi;

class PdfSignCertificateAdder
{
	private Fpdi $pdf;
	private string $privateKeyPath;
	private string $certificate;
	private string $password;

	const HASH_ALGO = 'sha256';
	const SIGN_FIELD_NAME = 'signature_field_name';
	const SIGN_ALGO = OPENSSL_ALGO_SHA256;

	/**
	 * PdfSignCertificateAdder constructor.
	 *
	 * @param string $pdfFile
	 * @param string $privateKeyPath
	 * @param string $certificate
	 * @param string $password
	 *
	 * @throws PdfParserException
	 */
	public function __construct(string $pdfFile, string $privateKeyPath, string $certificate, string $password)
	{
		$this->pdf = new Fpdi();
		$this->privateKeyPath = $privateKeyPath;
		$this->certificate = $certificate;
		$this->password = $password;
		$this->pdf->setSourceFile($pdfFile);
	}


	public function addCertificate($hash): void
	{
		$privateKey = $this->getPrivateKey();
		if($privateKey === false)
			throw new \Exception("Failed to retrieve Private Key");
		openssl_sign($hash, $signature, $privateKey, self::SIGN_ALGO);
		unset($privateKey);

		$this->pdf->setSignature($signature, $this->certificate, $this->password);
	}

	private function getPrivateKey(): \OpenSSLAsymmetricKey
	{
		return openssl_pkey_get_private(file_get_contents($this->privateKeyPath));
	}

	/**
	 * @throws PdfParserException
	 */
	public function addCertificateToSignedPdf(string $signedPdf, string $certPath): void
	{
		$certificate = file_get_contents($certPath);
		if ($certificate === false) {
			throw new \Exception("Error loading Certificate");
		}

		$oldPdf = $this->pdf;
		$this->pdf = new Fpdi();
		$this->pdf->setSourceFile($signedPdf);

		// add the loaded certificate
		$this->pdf->setSignature(null, $certificate);

		// resave the signed PDF with now the added certificate
		$this->pdf->Output($signedPdf, 'F');

		$this->pdf = $oldPdf;
	}
}

// Example usage:
$pdfFile = 'path/to/your/input.pdf';
$signatureImage = 'path/to/your/signature.png';
$outputFile = 'path/to/your/signed_output.pdf';
$privateKeyPath = 'path/to/your/private-key.pem';
$certificate = 'path/to/your/certificate.pem';
$password = 'your_certificate_password';

	$signer = new PdfSignCertificateAdder(
		$pdfFile,
		$signatureImage,
		$outputFile,
		$privateKeyPath
	);
$signer->addCertificateToSignedPdf();

