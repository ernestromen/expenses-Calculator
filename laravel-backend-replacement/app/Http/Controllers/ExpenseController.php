<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
class ExpenseController extends Controller
{
    public function getAllExpenses(){

        try {

            $expenses = Expense::all();
            $totalSum = Expense::sum('amount');
            return response()->json([$expenses,'totalSum'=>$totalSum]);
        
        } catch (\Exception $e) {
            return view('404',["message"=> $e->getMessage()]);
        }
    }

    public function addExpense(Request $request){

        try {

            Expense::insert($request->all());
            $totalSum = Expense::sum('amount');

            return response()->json(['totalSum'=>$totalSum]);
        
        } catch (\Exception $e) {
            // dd( '$e->getMessage()');
            dd( $e->getMessage());
            return view('404',["message"=> $e->getMessage()]);
        }
    }
    
    public function deleteExpense($id){
            try {
                Expense::where('id', $id)->delete();
                $totalSum = Expense::sum('amount');

                return response()->json(['totalSum'=>$totalSum]);
            
            } catch (\Exception $e) {
                return view('404',["message"=> $e->getMessage()]);
            }
        }
    
    public function deleteExpenses(Request $request){
            try {
                Expense::destroy($request->all());
                $totalSum = Expense::sum('amount');

                return response()->json(['totalSum'=>$totalSum]);
            
            } catch (\Exception $e) {
                return view('404',["message"=> $e->getMessage()]);
            }
        }

    
    //Laravel query builder re-write
    public function buildQuery($purchasetype=null, $timeline =null){


    }


    public function searchExpenses($purchasetype=null, $timeline =null){
   
        $codition1 = '';
        $codition2 = '';

        if(!is_null($timeline)){
            switch ($timeline)
            {
                case "Today":
                    $codition1 =  'DATE_FORMAT(created_at, "%d") = DAY(NOW())';
                  break;
                case "Monthly":
                    $codition1 = 'DATE_FORMAT(created_at, "%m") = MONTH(NOW())';
                break;
                case "Yearly":
                    $codition1 = 'DATE_FORMAT(created_at, "%Y") = YEAR(NOW())';
                  break;
                default:
                    $codition1 =false;
            }
        }

        if(!is_null($purchasetype)){
            switch ($purchasetype)
            {
                case "Food":
                    $codition2 =  '`purchasetype` = "Food"';
                  break;
                case "Recreational":
                    $codition2 =  '`purchasetype` = "Recreational"';
                break;
                case "Utilities":
                    $codition2 =  '`purchasetype` = "Utilities"';
                  break;
                case "All":
                    $codition2 = 'All';
                  break;
                default:
                    $codition2 =false;
            }
        }

            $query = '';
            if($codition2 && !$codition1){
                $query = $codition2;
            }else if(!$codition2 && $codition1){
                $query = $codition1;

            }else if($codition1 && $codition2){
                $query = $codition1.' AND '.$codition2;
            }


        try {
             if($codition2 == 'All' && !$codition1){
                $result = Expense::All();
            }else if($codition2 == 'All' && $codition1){
                $result = Expense::select('id', 'purchasetype', 'amount', 'created_at')
                ->whereRaw($codition1)
                ->get();
            }else{
                $result = Expense::select('id', 'purchasetype', 'amount', 'created_at')
                ->whereRaw($query)
                ->get();
            }
            

            $expenses = [];

            foreach ($result as $expense) {
                $expenses[] = [
                    "id" => $expense->id,
                    "purchasetype" => $expense->purchasetype,
                    "amount" => $expense->amount,
                    "created_at" => $expense->created_at,
                ];
            }

            $totalSum = Expense::sum('amount');

            return response()->json([$expenses,'totalSum'=>$totalSum]);

            
            } catch (\Exception $e) {
                return view('404',["message"=> $e->getMessage()]);
            }
        }



}
