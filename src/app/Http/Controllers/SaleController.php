<?php

namespace App\Http\Controllers;

use App\Http\Requests\Sale\IndexRequest;
use App\Http\Requests\Sale\ShowRequest;
use App\Http\Requests\Sale\UpdateRequest;
use App\Models\Sale;
use App\Services\SaleService;
use Illuminate\Http\Request;
use App\Http\Requests\Sale\CreateRequest as SaleCreateRequest;

class SaleController extends Controller
{
    private SaleService $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->saleService = $saleService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(IndexRequest $request)
    {
        try {
            $filter = $request->validated();
            $sales = $this->saleService->list($filter);
            if (empty($sales)) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Nenhuma venda encontrada!'
                ], 404);
            }
            return response()->json($sales);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao listar as vendas! - Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SaleCreateRequest $request)
    {
        try {
            $data = $request->validated();
            if ($this->saleService->store($data)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Venda cadastrada com sucesso!'
                ], 201);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao cadastrar a venda!'
            ], 500);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao cadastrar a venda! - Exception: '. $e->getMessage()
            ], 500);
        }

    }

    /**
     * Display the specified resource.
     */
    public function show(ShowRequest $request)
    {
        try {

            $id = $request->validated()['id'];
            $sale = $this->saleService->getById($id);
            if (!empty($sale)) {
                return response()->json($sale);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Venda não encontrada!'
            ], 404);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao buscar a venda! - Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request)
    {
        try {
            $data = $request->validated();
            if ($this->saleService->update($data)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Venda atualizada com sucesso!'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar a venda!'
            ], 500);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao atualizar a venda! - Exception: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShowRequest $request)
    {
        try {
            $id = $request->validated()['id'];
            if ($this->saleService->destroy($id)) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Venda cancelada com sucesso!'
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => 'Venda já cancelada!'
            ], 500);
        }catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Erro ao excluir a venda! - Exception: ' . $e->getMessage()
            ], 500);
        }
    }
}
