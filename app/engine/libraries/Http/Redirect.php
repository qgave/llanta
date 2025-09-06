<?php

namespace Engine\Libraries\Http;

class Redirect {
    protected Request $request;

    public function __construct(Request $request) {
        $this->request = $request;
    }

    public function url(string $url): RedirectResponse {
        return new RedirectResponse(normalizeUrl($url));
    }

    public function to($path): RedirectResponse {
        return $this->url($this->request->getBaseUrl() . $path);
    }

    public function back(): RedirectResponse {
        $previousPath = $this->request->getPreviousPath();
        if ($previousPath === null) return $this->refresh();
        return $this->to($previousPath);
    }

    public function refresh(): RedirectResponse {
        $url = $this->request->getURL();
        return new RedirectResponse($url, ['Refresh' => '0;url=' . $url]);
    }
}
