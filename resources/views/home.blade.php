<x-main-layout pageTitle="Countries and Capitals Quiz">

    <!-- form -->
    <div class="container mt-3">
        <div class="row justify-content-center">

            <div class="col-4 text-center">

                <form action="{{ route('prepare_game') }}" method="post">

                    @csrf

                    <div class="mt-3 mb-5">

                        <label class="form-label display-6 mb-3" for="total_questions">Número de perguntas:</label>
                        <input class="form-control form-control-lg text-center" type="number" name="total_questions" id="total_questions" min="3" max="30" value="10" required>

                        @error('total_questions')
                            <div class="text-danger text-center">{{$message}}</div>
                        @enderror

                    </div>

                    <div class="text-center">
                        <button class="btn btn-primary px-5" type="submit">INICIAR QUESTIONÁRIO</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</x-main-layout>
