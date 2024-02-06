<?php

namespace Management\Classes;

class Cache
{
    /**
     * Directory where cache files will be stored
     * @var string|null
     */
    public ?string $cache_directory = null;

    /**
     * Sub folder in cache directory optional
     * @var string|null
     */
    public ?string $sub_folder = null;

    /**
     * Cache constructor
     * @param string cache directory
     */
    public function __construct(string $cache_directory)
    {
        $this->cache_directory = $cache_directory;
        $this->create_directory($this->cache_directory);
    }

    /**
     * Create directory if doesn't exists
     * @param string directory
     */
    public function create_directory(string $directory)
    {
        if (!file_exists($directory)) {
            $oldmask = umask(0);
            @mkdir($directory, 0777, true);
            @umask($oldmask);
        }
    }

    /**
     * Set sub folder in cache directory. Like : {cache_directory}/{sub_folder} cache/pages_cache
     * @param string $sub_folder
     */
    public function set_sub_folder(string $sub_folder): void
    {
        $this->sub_folder = $sub_folder;
        $this->create_directory($this->cache_directory . DIRECTORY_SEPARATOR . $sub_folder);
    }

    /**
     * Get cache file
     * @param string $cache_name String that was used while creating cache.
     * @param int $max_age (in seconds). Return null if file older then these seconds. Default: 0 No limit
     * @param bool $delete_expired Delete cache if file age is more then max_age . Default: true
     * @return string|null Return null if file expired or doesn't exist
     */
    public function read(string $cache_name, int $max_age = 0, bool $delete_expired = true): ?string
    {
        $cache_file = $this->get_cache_path($cache_name);
        if ($this->check_cache($cache_name, $max_age, $delete_expired)) {
            return file_get_contents($cache_file);
        }
        return null;
    }

    /**
     * Create new cache file
     * @param string $cache_name Any string that will be used to access the cache in future
     * @param string $content Content
     */
    public function write(string $cache_name, string $content): void
    {
        $cache_file = $this->get_cache_path($cache_name);
        file_put_contents($cache_file, $content);
    }

    /**
     * Delete cache single file
     * @param string $cache_name
     */
    public function delete(string $cache_name): void
    {
        @unlink($this->get_cache_path($cache_name));
    }

    /**
     * Clear specific cache.
     * @param int $max_age (in seconds) . Delete all files older then these seconds. Defailt : 0 , Clear all files
     */
    public function clear(int $max_age = 0): void
    {
        $cache_dir = $this->get_cache_dir();
        foreach (array_diff(scandir($cache_dir), ['.', '..']) as $file) {
            $cache_file = $cache_dir . DIRECTORY_SEPARATOR . $file;
            if (is_file($cache_file) && ($max_age == 0 || (time() - filemtime($cache_file)) >= $max_age)) {
                unlink($cache_file);
            }
        }
    }

    /**
     * Clear all cache file
     */
    public function clear_all(): bool
    {
        return  $this->delete_directory($this->cache_directory);
    }

    /**
     * Delete dir
     * @param $dir
     */
    public function delete_directory($dir): bool
    {
        if (!file_exists($dir)) {
            return true;
        }
        if (!is_dir($dir)) {
            return unlink($dir);
        }
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            if (!$this->delete_directory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            };
        }
        return rmdir($dir);
    }

    /**
     * Check is cache exist or not
     * @param string
     */
    public function check_cache(string $cache_name, int $max_age = 0, bool $delete_expired = true): bool
    {
        $cache_file = $this->get_cache_path($cache_name);
        if (file_exists($cache_file)) {
            if ($max_age == 0 || (time() - filemtime($cache_file)) <= $max_age) {
                return true;
            } else if ($delete_expired) {
                $this->delete($cache_name);
            }
        }
        return false;
    }

    /**
     * Get ful path of cache file
     * @param string $cache_name String taht was used while creating cache
     * @return string
     */
    public function get_cache_path(string $cache_name): string
    {
        return $this->get_cache_dir() . DIRECTORY_SEPARATOR . hash('sha1', $cache_name) . ".cache";
    }

    /**
     * Get current cache directory with selected
     * @return string
     */
    public function get_cache_dir(): string
    {
        return is_null($this->sub_folder) ? $this->cache_directory : $this->cache_directory . DIRECTORY_SEPARATOR . $this->sub_folder;
    }

    /**
     * Get cache modification time
     * @param string $cache_name String that was used while creating cache
     * @return int|null 
     */
    public function get_time(string $cache_name): ?int
    {
        $cache_file = $this->get_cache_path($cache_name);
        if (file_exists($cache_file)) {
            return filemtime($cache_file);
        }
        return null;
    }
}
