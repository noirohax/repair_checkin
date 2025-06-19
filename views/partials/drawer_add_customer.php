<div id="drawer-add-customer" class="fixed inset-0 z-[9999] hidden overflow-hidden">
  <div class="absolute inset-0 bg-black bg-opacity-30" onclick="closeCustomerDrawer()"></div>
  <div class="absolute inset-y-0 right-0 w-full max-w-xl bg-white shadow-lg transition transform duration-300 ease-in-out flex flex-col">
    <div class="absolute top-4 right-4 z-10">
      <button type="button" class="text-gray-400 hover:text-gray-600" onclick="closeCustomerDrawer()">
        <span class="sr-only">Close panel</span>
        <i class="fa fa-times fa-lg"></i>
      </button>
    </div>
    <div class="overflow-y-auto p-6 mt-20">
      <h2 class="text-xl font-semibold mb-4">Add New Customer</h2>
      <div id="customer-drawer-form">
        <div class="form-group col-md-12">
          <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
        </div>
        <div class="form-group col-md-12">
          <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
        </div>
        <div class="form-group col-md-12">
          <input type="email" name="email" class="form-control" placeholder="Email Address">
        </div>
        <div class="form-group col-md-12">
          <input type="text" id="phonenumber"name="phonenumber" class="form-control" placeholder="Phone Number" required>
        </div>
        <div class="form-group col-md-12">
          <input type="text" name="company" class="form-control" placeholder="Company (optional)">
        </div>
        <div class="form-actions text-right">
          <button type="button" class="btn btn-default" onclick="closeCustomerDrawer()">Cancel</button>
          <button type="button" class="btn btn-primary" id="create-customer-btn">Create Customer</button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
const API_URL = 'https://portal.nashnerds.com/graphql';
const API_TOKEN = '1745466804';

function openCustomerDrawer() {
  document.getElementById('drawer-add-customer').classList.remove('hidden');
}

function closeCustomerDrawer() {
  document.getElementById('drawer-add-customer').classList.add('hidden');
}

function escapeGraphQL(value) {
  return value.replace(/\\/g, '\\\\').replace(/"/g, '\\"');
}

document.getElementById('create-customer-btn').addEventListener('click', async function () {
  const f = document.getElementById('customer-drawer-form');
  const firstname = escapeGraphQL(f.querySelector('[name="firstname"]').value.trim());
  const lastname = escapeGraphQL(f.querySelector('[name="lastname"]').value.trim());
  const phone = escapeGraphQL(f.querySelector('[name="phonenumber"]').value.trim());
  const email = escapeGraphQL(f.querySelector('[name="email"]').value.trim());
  const companyInput = f.querySelector('[name="company"]').value.trim();
  const company = escapeGraphQL(companyInput || `${firstname} ${lastname}`);
  const password = Math.random().toString(36).slice(-6) + "Aa1!";
  const today = new Date().toISOString().split("T")[0];

  const mutationClient = `
    mutation {
      addTblclients(
        company: "${company}",
        phonenumber: "${phone}",
        datecreated: "${today}",
        active: "1",
        addedfrom: "1"
      ) {
        message
      }
    }
  `;

  const queryAllClients = `
    query {
      tblclients {
        userid
        company
        datecreated
      }
    }
  `;

  try {
    const resClient = await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'authtoken': API_TOKEN
      },
      body: JSON.stringify({ query: mutationClient })
    });

    const addedClient = await resClient.json();
    console.log("📥 Client Response", addedClient);

    const resAllClients = await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'authtoken': API_TOKEN
      },
      body: JSON.stringify({ query: queryAllClients })
    });

    const allClients = await resAllClients.json();
    const clients = allClients?.data?.tblclients || [];
    const sorted = clients.sort((a, b) => Number(b.userid) - Number(a.userid));
    const clientId = sorted[0]?.userid;

    if (!clientId) {
      return alert("❌ Could not get latest client ID");
    }

    const mutationContact = `
      mutation {
        addTblcontacts(
          firstname: "${firstname}",
          lastname: "${lastname}",
          email: "${email}",
          phonenumber: "${phone}",
          userid: "${clientId}",
          password: "${password}"
        ) {
          id
        }
      }
    `;

    const resContact = await fetch(API_URL, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'authtoken': API_TOKEN
      },
      body: JSON.stringify({ query: mutationContact })
    });

    const contactJson = await resContact.json();
    console.log("📥 Contact Response", contactJson);

    if (contactJson.errors) {
      console.warn("⚠️ Contact created but with errors:", contactJson.errors);
    }

    const $customerSelect = $('#customer_id');
    const newOption = new Option(company, clientId, true, true);
    $customerSelect.append(newOption).val(clientId).trigger('change').selectpicker('refresh');

    closeCustomerDrawer();
  } catch (err) {
    console.error("❌ Unexpected error:", err);
    alert("❌ Unexpected error — see console");
  }
});
</script>
