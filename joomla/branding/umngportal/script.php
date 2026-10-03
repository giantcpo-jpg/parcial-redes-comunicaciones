<?php

defined('_JEXEC') or die;

use Joomla\CMS\Factory;
use Joomla\Database\DatabaseInterface;

class umngportalInstallerScript
{
    public function install($parent)
    {
        return true;
    }

    public function update($parent)
    {
        return true;
    }

    public function uninstall($parent)
    {
        return true;
    }

    public function preflight($type, $parent)
    {
        return true;
    }

    public function postflight($type, $parent)
    {
        try {
            $db = Factory::getContainer()->get(DatabaseInterface::class);

            // Buscar el estilo de nuestra plantilla
            $query = $db->getQuery(true)
                ->select($db->quoteName('id'))
                ->from($db->quoteName('#__template_styles'))
                ->where($db->quoteName('template') . ' = ' . $db->quote('umngportal'))
                ->where($db->quoteName('client_id') . ' = 0');

            $db->setQuery($query);
            $styleId = (int) $db->loadResult();

            if ($styleId <= 0) {
                echo "No se encontró el estilo UMNG Portal.\n";
                return true;
            }

            // Quitar cualquier plantilla predeterminada del frontend
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__template_styles'))
                ->set($db->quoteName('home') . ' = 0')
                ->where($db->quoteName('client_id') . ' = 0');

            $db->setQuery($query);
            $db->execute();

            // Poner UMNG Portal como predeterminada
            $query = $db->getQuery(true)
                ->update($db->quoteName('#__template_styles'))
                ->set($db->quoteName('home') . ' = 1')
                ->where($db->quoteName('id') . ' = ' . $styleId);

            $db->setQuery($query);
            $db->execute();

            echo "UMNG Portal configurado como plantilla predeterminada.\n";

            return true;

        } catch (\Throwable $e) {
            echo "Error configurando UMNG Portal: "
                . $e->getMessage()
                . "\n";

            return true;
        }
    }
}