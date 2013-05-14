<?php
switch($A){
    case 'init':
        $smarty->assign($assign);
        $smarty->display('sales/contract.init.html');
        break;
    case 'base.edit':
        $smarty->assign($assign);
        $smarty->display('sales/contract.base.edit.html');
        break;
    case 'sales.add':
        
        break;
    case 'receive.plan.add':
        
        break;
    case 'purchase.plan.add':
        
        break;
    case 'expense.plan.add':
        
        break;
    case 'base.save':
        
        break;
    case 'sales.save':
        
        break;
    case 'receive.plan.save':
        
        break;
    case 'purchase.save':
        
        break;
    case 'expense.save':
        
        break;
    case 'delete':
        
        break;
}