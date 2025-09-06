<?php

namespace Engine\Libraries\Utilities;

use Engine\Libraries\Http\Request;

class SessionBag {
    public function __construct(Request $request) {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (!$this->exist('__llanta') || !is_array($this->get('__llanta'))) {
            return $this->set('__llanta', [
                'temp' => [],
                'temp_delete' => [],
                'path' => $request->getPath(),
            ]);
        }
        $llanta = $this->get('__llanta');

        if (isset($llanta['temp']) && is_array($llanta['temp'])) {
            foreach ($llanta['temp'] as $key => $value) {
                $this->set($key, $value);
            }
        }
        if (isset($llanta['temp_delete']) && is_array($llanta['temp_delete'])) {
            foreach ($llanta['temp_delete'] as $key => $value) {
                $this->delete($key);
            }
        }
        $llanta['temp_delete'] = $llanta['temp'];
        $llanta['temp'] = [];

        if (isset($llanta['path']) && is_string($llanta['path'])) {
            $request->setPreviousPath($llanta['path']);
        }

        $llanta['path'] = $request->getPath();

        return $this->set('__llanta', $llanta);
    }

    public function get(string $key): mixed {
        return $_SESSION[$key] ?? null;
    }

    public function set(string $key, $value): void {
        $_SESSION[$key] = $value;
    }

    public function delete(string $key): bool {
        if (isset($_SESSION[$key])) {
            unset($_SESSION[$key]);
            return true;
        }
        return false;
    }

    public function clear(): void {
        $_SESSION = [];
    }

    public function temp(string $key, $value): void {
        $llanta = $this->get('__llanta');
        if (isset($llanta['temp']) || is_array($llanta['temp'])) {
            $temp = $llanta['temp'];
            $temp[$key] = $value;
        } else {
            $temp = [$key => $value];
        }
        $llanta['temp'] = $temp;
        $this->set('__llanta', $llanta);
        //sesion que solo existe para la proxima ejecucion
    }

    public function exist($key): bool {
        return isset($_SESSION[$key]);
    }
}
