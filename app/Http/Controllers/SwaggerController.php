<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SwaggerController extends Controller
{
    /**
     * Display Swagger UI
     */
    public function ui()
    {
        $swaggerHtml = file_get_contents(base_path('swagger-ui.html'));
        return response($swaggerHtml)->header('Content-Type', 'text/html');
    }

    /**
     * Serve Swagger YAML file
     */
    public function yaml()
    {
        $swaggerYaml = file_get_contents(base_path('swagger.yaml'));
        return response($swaggerYaml)
            ->header('Content-Type', 'application/x-yaml')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    }

    /**
     * Serve Swagger JSON (fallback - serves YAML with JSON content type)
     */
    public function json()
    {
        $swaggerYaml = file_get_contents(base_path('swagger.yaml'));

        // For now, we'll serve the YAML content with proper headers
        // In production, you might want to install symfony/yaml for proper conversion
        return response($swaggerYaml)
            ->header('Content-Type', 'application/json')
            ->header('Access-Control-Allow-Origin', '*')
            ->header('Access-Control-Allow-Methods', 'GET')
            ->header('Access-Control-Allow-Headers', 'Content-Type');
    }
}
