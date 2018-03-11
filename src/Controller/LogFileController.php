<?php
/**
 * Created by PhpStorm.
 * User: mamazu
 * Date: 11/03/18
 * Time: 14:34
 */

namespace App\Controller;


use App\Entity\LogFile;
use App\Repository\LogFileRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class LogFileController extends Controller
{
	/**
	 * @var LogFileRepository
	 */
	private $logFileRepository;

	public function __construct(LogFileRepository $logFileRepository)
	{
		$this->logFileRepository = $logFileRepository;
	}

	public function show(Request $request, int $buildJobId): Response
	{
		/** @var LogFile $logFile */
		$logFile    = $this->logFileRepository->getLogFileByBuildJobId($buildJobId);
		$statusCode = $logFile === null ? 204 : 200;

		$content    = $statusCode === 200 ? $logFile->getContent() : '';
		$createDate = $statusCode === 200 ? $logFile->getCreatedAt()->format(DATE_ISO8601) : '';

		if ($request->getContentType() === 'json') {
			return new JsonResponse("\{\"content\": \"$content\", \"createDate\": \"$createDate\"\}", $statusCode);
		}

		return new Response("<p>Created at: $createDate</p><br/><p>$content</p>", $statusCode);
	}
}