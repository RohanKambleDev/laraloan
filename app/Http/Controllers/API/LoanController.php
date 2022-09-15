<?php

namespace App\Http\Controllers\API;

use Carbon\Carbon;
use App\Models\Loan;
use App\Models\Status;
use Illuminate\Support\Str;
use App\Traits\API\RestTrait;
use Illuminate\Http\Response;
use App\Models\ScheduledPayment;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Services\LoanService;
use App\Http\Requests\API\Loan\CreateRequest;
use App\Http\Requests\API\Loan\ApproveRequest;
use App\Http\Requests\API\Loan\PaymentRequest;
use function PHPUnit\Framework\throwException;
use Illuminate\Http\Exceptions\HttpResponseException;
use index;

class LoanController extends Controller
{
    use RestTrait;

    /**
     * list all loan taken by the logged in user
     * and if Admin is logged in then all the loans
     * of all users will be displayed
     *
     * @param  Loan $loan
     * @param  LoanService $loanService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Loan $loan, LoanService $loanService)
    {
        $responseData = $loanService->listLoans($loan);
        return $this->successResponse($responseData['data'], $responseData['message'], $responseData['statusCode']);
    }

    /**
     * create a Loan
     *
     * @param  CreateRequest $request
     * @param  Loan $loan
     * @param  LoanService $loanService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function create(CreateRequest $request, Loan $loan, LoanService $loanService)
    {
        $requestData = $request->validated(); // get validated request data
        $responseData = $loanService->create($requestData, $loan);
        return $this->successResponse($responseData['data'], $responseData['message'], $responseData['statusCode']);
    }

    /**
     * show individual loan details
     *
     * @param  string $uuid
     * @param  Loan $loan
     * @param  LoanService $loanService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($uuid, Loan $loan, LoanService $loanService)
    {
        $responseData = $loanService->viewLoan($uuid, $loan);
        return $this->successResponse($responseData['data'], $responseData['message'], $responseData['statusCode']);
    }

    /**
     * To make a payment againt a Loan installment
     *
     * @param  PaymentRequest $request
     * @param  ScheduledPayment $scheduledPayment
     * @param  LoanService $loanService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function makePayment(PaymentRequest $request, ScheduledPayment $scheduledPayment, LoanService $loanService)
    {
        $requestData = $request->validated(); // get validated request data
        $responseData = $loanService->repayLoan($requestData, $scheduledPayment);
        return $this->successResponse($responseData['data'], $responseData['message'], $responseData['statusCode']);
    }

    /**
     * approve
     *
     * @param  ApproveRequest $request
     * @param  Loan $loan
     * @param  LoanService $loanService
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function approve(ApproveRequest $request, Loan $loan, LoanService $loanService)
    {
        $requestData = $request->validated(); // get validated request data
        $responseData = $loanService->approveLoan($requestData, $loan);
        return $this->successResponse($responseData['data'], $responseData['message'], $responseData['statusCode']);
    }
}
