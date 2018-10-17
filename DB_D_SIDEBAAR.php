<?php
 
    $refs = array();
    $list = array();
    
		
		include("DbCon.php");
		$sql = "SELECT id as menu_item_id, parent_id as menu_parent_id, title as menu_item_name,concat('/foldername',url)as url,menu_order,icon FROM dynamic_menu ORDER BY menu_order";								
		$result = mysqli_query($con,$sql);
		while ($data=mysqli_fetch_array($result))
		{ 
		
		
        $thisref = &$refs[ $data['menu_item_id'] ];
        $thisref['menu_parent_id'] = $data['menu_parent_id'];
        $thisref['menu_item_name'] = $data['menu_item_name'];
 $thisref['url'] = $data['url'];
 $thisref['icon'] = $data['icon'];
        if ($data['menu_parent_id'] == 0)
        {
            $list[ $data['menu_item_id'] ] = &$thisref;
        }
        else
        {
            $refs[ $data['menu_parent_id'] ]['children'][ $data['menu_item_id'] ] = &$thisref;
        }
    }
    function create_list( $arr ,$urutan)
    {
 if($urutan==0){
       $html = "\n<ul class='sidebar-menu'>\n";
 }else
 {
 $html = "\n<ul class='treeview-menu'>\n";
 }
        foreach ($arr as $key=>$v)
        {
            if (array_key_exists('children', $v))
            {
                $html .= "<li class='treeview'>\n";
 $html .= '<a href="#">
                                <i class="'.$v['icon'].'"></i>
                                <span>'.$v['menu_item_name'].'</span>
                                <i class="fa fa-angle-left pull-right"></i>
                            </a>';
 
                $html .= create_list($v['children'],1);
                $html .= "</li>\n";
            }
            else{
 $html .= '<li><a href="'.$v['url'].'">';
 if($urutan==0)
 {
 $html .= '<i class="'.$v['icon'].'"></i>';
 }
 if($urutan==1)
 {
 $html .= '<i class="fa fa-angle-double-right"></i>';
 }
 $html .= $v['menu_item_name']."</a></li>\n";}
        }
        $html .= "</ul>\n";
        return $html;
    }
    echo create_list( $list,0 );
?>