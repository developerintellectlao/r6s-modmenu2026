<?php

class WPvivid_Staging_Connect_server
{
    private $url='https://wpvivid.com/wc-api/wpvivid_api';
    private $update_url='https://download.wpvivid.com';

    public function login($user_info,$encrypt_user_info,$get_key=false)
    {

        if($get_key)
            $public_key='';
        else
            $public_key=get_option('wpvivid_connect_key','');

        if(empty($public_key))
        {
            $public_key=$this->get_key();
            if($public_key===false)
            {
                $ret['result']='failed';
                $ret['error']='An error occurred when connecting to WPvivid Backup Pro server. Please try again later or contact us.';
                return $ret;
            }
            update_option('wpvivid_connect_key',$public_key);
        }

        $crypt=new WPvivid_Staging_crypt($public_key);

        if($encrypt_user_info)
        {
            $user_info=$crypt->encrypt_user_token($user_info);
            $user_info=base64_encode($user_info);
        }


        $crypt->generate_key();

        $json['user_info']=$user_info;

        $json['domain'] = $this->get_domain();
        $json=json_encode($json);
        $data=$crypt->encrypt_message($json);

        $action='get_dashboard_status';
        $url=$this->url;
        $url.='?request='.$action;
        $url.='&data='.rawurlencode(base64_encode($data));

        $ret=$this->remote_request($url);

        if($ret['result']=='success')
        {
            if($encrypt_user_info)
            {
                $ret['user_info']=$user_info;
            }
            return $ret;
        }
        else
        {
            return $ret;
        }
    }

    public function active_site($user_info,$encrypt_user_info=false)
    {
        $public_key=get_option('wpvivid_connect_key','');

        if(empty($public_key))
        {
            $public_key=$this->get_key();
            if($public_key===false)
            {
                $ret['result']='failed';
                $ret['error']='An error occurred when connecting to WPvivid Backup Pro server. Please try again later or contact us.';
                return $ret;
            }
            update_option('wpvivid_connect_key',$public_key);
        }

        $crypt=new WPvivid_Staging_crypt($public_key);

        if($encrypt_user_info)
        {
            $user_info=$crypt->encrypt_user_token($user_info);
            $user_info=base64_encode($user_info);
        }

        $crypt->generate_key();

        $json['user_info']=$user_info;

        $json['domain'] = $this->get_domain();

        $json=json_encode($json);
        $data=$crypt->encrypt_message($json);
        $action='active_dashboard_site';
        $url=$this->url;
        $url.='?request='.$action;
        $url.='&data='.rawurlencode(base64_encode($data));
        $options=array();
        $options['timeout']=30;

        $ret=$this->remote_request($url);

        if($ret['result']=='success')
        {
            if($encrypt_user_info)
            {
                $ret['user_info']=$user_info;
            }

            return $ret;
        }
        else
        {
            return $ret;
        }
    }

    public function remote_request($url,$body=array())
    {
        $options=array();
        $options['timeout']=30;
        if(empty($options['body']))
        {
            $options['body']=$body;
        }

        $retry=0;
        $max_retry=3;

        $ret['result']='failed';
        $ret['error']='remote request failed';

        while($retry<$max_retry)
        {
            $request=wp_remote_request($url,$options);

            if(!is_wp_error($request) && ($request['response']['code'] == 200))
            {
                $json= wp_remote_retrieve_body($request);
                $body=json_decode($json,true);

                if(is_null($body))
                {
                    $ret['result']='failed';
                    $ret['error']='Decoding json failed. Please try again later.';
                }

                if(isset($body['result'])&&$body['result']=='success')
                {
                    return $body;
                }
                else
                {
                    if(isset($body['result'])&&$body['result']=='failed')
                    {
                        $ret['result']='failed';
                        $ret['error']=$body['error'];
                        if(isset($body['error_code']))
                        {
                            $ret['error_code']=$body['error_code'];
                        }
                    }
                    else if(isset($body['error']))
                    {
                        $ret['result']='failed';
                        $ret['error']=$body['error'];
                        if(isset($body['error_code']))
                        {
                            $ret['error_code']=$body['error_code'];
                        }
                    }
                    else
                    {
                        $ret['result']='failed';
                        $ret['error']='login failed';
                        $ret['test']=$body;
                    }
                }
            }
            else
            {
                $ret['result']='failed';
                if ( is_wp_error( $request ) )
                {
                    $error_message = $request->get_error_message();
                    $ret['error']="Sorry, something went wrong: $error_message. Please try again later or contact us.";
                }
                else if($request['response']['code'] != 200)
                {
                    $ret['error']=$request['response']['message'];
                }
                else {
                    $ret['error']=$request;
                }
            }

            $retry++;
        }


        return $ret;
    }

    public function get_key()
    {
        $options=array();
        $options['timeout']=30;
        $request=wp_remote_request($this->url.'?request=get_key',$options);

        if(!is_wp_error($request) && ($request['response']['code'] == 200))
        {
            $json= wp_remote_retrieve_body($request);
            $body=json_decode($json,true);
            if(is_null($body))
            {
                return false;
            }

            if($body['result']=='success')
            {
                $public_key=base64_decode($body['public_key']);
                if($public_key==null)
                {
                    return false;
                }
                else
                {
                    return $public_key;
                }
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function get_download_link()
    {
        $info= get_option('wpvivid_pro_user',false);
        if($info===false||!isset($info['token']))
        {
            return '';
        }
        else
        {
            $user_info=$info['token'];
            $public_key=get_option('wpvivid_connect_key','');
            if(empty($public_key))
            {
                return '';
            }
            $crypt=new WPvivid_Staging_crypt($public_key);
            $crypt->generate_key();
            $json['user_info']=$user_info;

            $json['domain'] = $this->get_domain();
            $json['staging_update']=1;

            $json=json_encode($json);

            $data=$crypt->encrypt_message($json);

            $url='https://update.wpvivid.com';
            $url.='?data='.rawurlencode(base64_encode($data));

            return $url;
        }
    }

    public function get_domain()
    {
        global $wpdb;
        $home_url = home_url();
        $db_home_url = home_url();
        $home_url_sql = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->options WHERE option_name = %s", 'home' ) );
        foreach ( $home_url_sql as $home ){
            $db_home_url = untrailingslashit($home->option_value);
        }
        if($home_url === $db_home_url)
        {
            $domain = $home_url;
        }
        else
        {
            $domain = $db_home_url;
        }

        $domain=apply_filters('wpvivid_get_login_domain',$domain);

        return strtolower($domain);
    }
}