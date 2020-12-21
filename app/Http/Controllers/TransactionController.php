<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $transaction = Transaction::where('title', 'LIKE','%'.$request->search.'%')->orderBy('time','desc')->paginate(1);
        $response = [
            'message' => 'List transaction order by time',
            'data' => $transaction
        ];
        return response()->json($response, Response::HTTP_OK);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => ['required','numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(),
            Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        try{
            $transaction = Transaction::create($request->all());
            return response()->json([
                'message' => 'Transaction Created',
                'data' => $transaction
            ]);
        }catch(QueryException $e){
            return response()->json(
                [
                    'message' => 'Failed '.$e->getMessage()
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $transaction = Transaction::findOrFail($id);
            return response()->json([
                'message' => 'Get Transaction',
                'data' => $transaction
            ]);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'message' => 'Failed ' . $e->errorInfo
                ]
            );
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'amount' => ['required', 'numeric'],
            'type' => ['required', 'in:expense,revenue']
        ]);

        if ($validator->fails()) {
            return response()->json(
                $validator->errors(),
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        try {
            $transaction->update($request->all());
            $response = [
                'message' => 'Transaction Updated',
                'data' => $transaction
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'message' => 'Failed ' . $e->errorInfo
                ]
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $transaction = Transaction::findOrFail($id);
        try {
            $transaction->delete();
            $response = [
                'message' => 'Transaction Deleted'
            ];
            return response()->json($response, Response::HTTP_OK);
        } catch (QueryException $e) {
            return response()->json(
                [
                    'message' => 'Failed ' . $e->errorInfo
                ]
            );
        }
    }
}
