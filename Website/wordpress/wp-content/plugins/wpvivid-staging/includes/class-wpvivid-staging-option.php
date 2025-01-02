<?php

if (!defined('WPVIVID_STAGING_PLUGIN_DIR'))
{
    die;
}

class WPvivid_Staging_Option
{
    public $options_table;

    public function __construct()
    {
        global $wpdb;
        $this->options_table = $wpdb->base_prefix."wpvivid_options";
        $this->check_tables();
    }

    public function check_tables()
    {
        global $wpdb;

        if($wpdb->get_var("SHOW TABLES LIKE '$this->options_table'") != $this->options_table)
        {
            $sql = "CREATE TABLE IF NOT EXISTS $this->options_table (
                `option_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                `option_name` varchar(191) NOT NULL DEFAULT '',
				`option_value` longtext NOT NULL,
				PRIMARY KEY (`option_id`),
				UNIQUE KEY `option_name` (`option_name`)
                );";
            //reference to upgrade.php file
            $wpdb->query($sql);
        }
        /*else
        {
            //fix staging free to pro
            $task_list = get_option('wpvivid_staging_task_list',array());
            $task_list_ex = $this->get_option('wpvivid_staging_task_list');
            if($task_list_ex === false)
            {
                $task_list_ex = array();
            }
            if(!empty($task_list))
            {
                $is_copy_staging_list_db = false;
                foreach ($task_list as $staging_id => $staging_info)
                {
                    if(!array_key_exists($staging_id, $task_list_ex))
                    {
                        $is_copy_staging_list_db = true;
                        $task_list_ex[$staging_id] = $staging_info;
                        unset($task_list[$staging_id]);
                    }
                }
                if($is_copy_staging_list_db)
                {
                    update_option('wpvivid_staging_task_list', $task_list);
                    $this->update_option('wpvivid_staging_task_list', $task_list_ex);
                }
            }
        }*/
    }

    public function get_option($option_name)
    {
        global $wpdb;

        $query =$wpdb->prepare('select option_value from '.$this->options_table .' where option_name = %s', $option_name);

        $result =$wpdb->get_var($query);
        if(empty($result))
        {
            return false;
        }
        else
        {
            return maybe_unserialize($result);
        }
    }

    public function update_option($option_name,$value)
    {
        global $wpdb;

        if($this->is_exists_option($option_name))
        {
            $option_value = maybe_serialize($value);
            return $wpdb->update($this->options_table, compact('option_name', 'option_value'), compact('option_name'));
        }
        else
        {
            $option_value = maybe_serialize($value);
            return $wpdb->insert($this->options_table, compact('option_name', 'option_value'));
        }
    }

    public function is_exists_option($option_name)
    {
        global $wpdb;

        $query = $wpdb->prepare('select option_value from '.$this->options_table.' where option_name = %s', $option_name);
        $result =$wpdb->get_row($query);
        return !empty($result);
    }
}