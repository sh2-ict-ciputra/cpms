<select name="satuan" class="form-control">
							<option {{ $a ? (('ls' == $a->satuan) ? 'selected' : '') : '' }}>ls</option>
							<option {{ $a ? (('m' == $a->satuan) ? 'selected' : '') : '' }}>m</option>
							<option {{ $a ? (('m2' == $a->satuan) ? 'selected' : '') : '' }}>m2</option>
							<option {{ $a ? (('m3' == $a->satuan) ? 'selected' : '') : '' }}>m3</option>
							<option {{ $a ? (('kg' == $a->satuan) ? 'selected' : '') : '' }}>kg</option>
						</select>