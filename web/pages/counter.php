<script type="text/javascript" src="counter.js"></script>
<h3>Zähler</h3>
<div id="counter-table">No Data / error</div>
<div style="border-bottom: 1px solid #ccc; height: 2px;"></div>
<h3>Zählerstand</h3>
<input class="form-control" type="date" onchange="updateCounternumber()"/>
<div style="border-bottom: 1px solid #ccc; height: 2px;"></div>
<h3>Neuer Zähler</h3>
<div id="new-counter">
    <form method="POST" action="api.php?q=addCounter">
        <div class="form-group">
            <label>Name</label>
            <input class="form-control" placeholder="Bezug # / Lieferung #" name="name" />
        </div>
        <div class="form-group">
            <label>Zählertyp</label>
            <select class="form-control" id="mode">
                <option>Bezug</option>
                <option>Lieferung</option>
            </select>
        </div>
        <input class="btn btn-primary" type="submit" value="Hinzufügen" />
    </form>
</div>
