<div class="border rounded p-3 mb-3">
  <div class="d-flex justify-content-between">
    <strong>Soal #{{ $qi+1 }}</strong>
    <button type="button" class="btn btn-sm btn-link text-danger" onclick="this.closest('.border').remove()">Hapus</button>
  </div>
  <div class="mt-2">
    <label>Pertanyaan</label>
    <textarea class="form-control" name="questions[{{ $qi }}][question]" rows="2" required>{{ $q->question }}</textarea>
  </div>
  <div class="mt-2">
    <label>Opsi</label>
    <div id="opts-{{ $qi }}">
      @foreach($q->options as $oi => $opt)
        <input class="form-control mb-2" name="questions[{{ $qi }}][options][{{ $oi }}]" value="{{ $opt->text }}" required>
      @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-secondary" onclick="addOpt({{ $qi }})">+ Opsi</button>
  </div>
  <div class="mt-2">
    <label>Index Jawaban Benar (mulai 0)</label>
    <input type="number" class="form-control" name="questions[{{ $qi }}][correct]" value="{{ $q->correct_index }}" min="0">
  </div>
</div>
