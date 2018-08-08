<?php declare(strict_types = 1);

namespace Mangoweb\MonologTracyHandler\MonologProcessors;

use Mangoweb\MonologTracyHandler\RemoteStorageDriver;


class TracyUrlProcessor
{
	/** @var string */
	private $localBlueScreenDirectory;

	/** @var RemoteStorageDriver */
	private $remoteStorageDriver;


	public function __construct(string $localBlueScreenDirectory, RemoteStorageDriver $remoteStorageDriver)
	{
		$this->localBlueScreenDirectory = $localBlueScreenDirectory;
		$this->remoteStorageDriver = $remoteStorageDriver;
	}


	public function __invoke(array $record): array
	{
		if (isset($record['context']['tracy_filename']) && is_string($record['context']['tracy_filename'])) {
			$localName = $record['context']['tracy_filename'];
			$localPath = "{$this->localBlueScreenDirectory}/{$localName}";
			$remoteUrl = $this->remoteStorageDriver->getUrl($localPath);

			if ($remoteUrl !== null) {
				$record['context']['tracy_url'] = $remoteUrl;
			}
		}

		return $record;
	}
}
