<?php
namespace Api\Controller\v1;

use Base\ConstDir\Api\ApiError;
use Base\ConstDir\Api\ApiSuccess;
use COM\Controller\Api;

class NearbyController extends Api{

    private $baiduApiUrl = 'http://api.map.baidu.com/telematics/v3/local?output=json&ak=6E39371418ca5b0d38da3f7a7f491376';

    /**
     * 附近加油站
     */
    public function placeAction(){

        $parameters = $this->request->getPost();
        $purview = array('Keyword', 'Locationx', 'Locationy', 'Page', 'Limit');
        $required = $purview;
        $data = $this->parametersFilter($parameters, $purview, $required);
        if(empty($data)) return $this->_return(ApiError::PARAMETER_MISSING, ApiError::PARAMETER_MISSING_MSG);

        $queryData = array();
        $queryData['page'] = $data['Page'];
        $queryData['number'] = $data['Limit'];
        $queryData['keyWord'] = $data['Keyword'];

        $queryData["location"] = $data['Locationx'] . "," . $data['Locationy'];

        $queryUrl = $this->baiduApiUrl . "&" . http_build_query($queryData);

        $ch = curl_init($queryUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = json_decode(curl_exec($ch), true);
        curl_close($ch);

        if(!empty($result)){
            if($result['status'] == 'Success'){
                $list = array();
                foreach ($result['pointList'] as $v) {
                    $list[] = array(
                        'name' => $v['name'], 'cityName' => $v['cityName'],
                        'locationx' => $v['location']['lng'], 'locationy' => $v['location']['lat'],
                        'address' => $v['address'], 'distance' => $v['distance'], 'tel' => $v['additionalInformation']['telephone']
                    );
                }

                return $this->_return(ApiSuccess::COMMON_SUCCESS, ApiSuccess::COMMON_SUCCESS_MSG, array('list' => $list));
            }else{
                return $this->_return(ApiError::DATA_QUERY_FAILED, ApiError::DATA_QUERY_FAILED_MSG);
            }
        }else{
            return $this->_return(ApiError::DATA_QUERY_FAILED, ApiError::DATA_QUERY_FAILED_MSG);
        }
    }
}