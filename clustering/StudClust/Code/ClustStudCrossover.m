function xoverKids = ClustStudCrossover(parents, options, GenomeLength, ...
    FitnessFcn, unused,thisPopulation)

% % % % qq = round(unused*10000)/10000== [0.9464    0.9585    0.9615    0.9684    0.9482    0.9600 ...
% % % %     0.9622    0.9569    0.9540    0.9611    0.9485    0.9577 ...
% % % %      0.9464    0.9528    0.9482    0.9582    0.9482    0.9549  ...
% % % %      0.9470    0.9597    0.9528    0.9460    0.9482    0.9482 ...
% % % %       0.9451    0.9725    0.9479    0.9468    0.9482    0.9835]';

% How many children to produce?
nKids = length(parents)/2;
% Allocate space for the kids
xoverKids = zeros(nKids,GenomeLength);

%How many clusters are there? 
nClust = max(thisPopulation(1,:));
%Fake some clusters scores - if we want we can actually compute them later
ClustScores = ones(size(thisPopulation,1),nClust);


%Histogram of clustersizes (constant across population)
SizeClust = histc(thisPopulation(1,:),1:max(thisPopulation(1,:))); %Count cluster sizes


% % % % if ~all(SizeClust==12|SizeClust==13), keyboard; end



ClustHist = histc(SizeClust,min(SizeClust):max(SizeClust));
ClustSizes = min(SizeClust):max(SizeClust);

% To move through the parents twice as fast as thekids are
% being produced, a separate index for the parents is needed
index = 1;
% for each kid...
for i=1:nKids
    
    
% % % % % %       if all(qq)&&i==21, keyboard; end
    
    % get parents
    r1 = parents(index);
    index = index + 1;
    r2 = parents(index);
    index = index + 1;
    
    %Reset this cluster histogram
    thisClustHist = ClustHist;
    thisClustSizes = ClustSizes;
    
    %Select the best nkeep clusters to keep
    [~,Is] = sort(ClustScores(r1,:)); %Choose clusterscore of one of the parents
    Ctokeep = Is(1:randi(nClust-1)); %Select a random number of top clusters
%     Ctokeep = Is(1);
    Ctokeep = Ctokeep';
    nCtokeep = length(Ctokeep); %Number of clusters to keep
    
    %Loop across the clusters to keep
    Itokeep = false(1,GenomeLength); %Contains index of the crossed over students
    for j = 1:nCtokeep
        thisclust = thisPopulation(r1,:)==Ctokeep(j);
        Itokeep = Itokeep |  thisclust;
        %Rename clusters to start at 1
        xoverKids(i,thisclust) = j;
        
        %Update the clustersize histogram
        Isize = thisClustSizes==sum(thisclust);
        thisClustHist(Isize) = thisClustHist(Isize) - 1;
    end
    nextclust = j+1;
    
    Itoplace = find(~Itokeep); %These need to be placed still
    Ctoplace = thisPopulation(r2,Itoplace); %These are their cluster indices
    while ~isempty(Itoplace)
        %sort the students based on cluster size
        [leftoverclusts,~,Ib] = unique(Ctoplace);
        leftoverclustsizes = histc(Ib,1:max(Ib));
        id2size = zeros(max(Ctoplace),1);
        id2size(leftoverclusts) = leftoverclustsizes;
        
        %Determine per student in which size cluster they are
        Itoplace_sizes = id2size(Ctoplace);
        %Sort smallest to largest AND cluster ID (to take care of same size
        %clusters)
        [~,Is] = sortrows([Itoplace_sizes(:) Ctoplace(:)],[1 2]);
        
        %Find the biggest cluster (and select if more than one)
        biggestclust = Ctoplace(Is(end));
        
        %Collect new cluster indices
        Iplace = Itoplace(Ctoplace==biggestclust);
        
        %choose a cluster target size
        targetsize = thisClustSizes(find(thisClustHist>0,1,'last'));
        
        %Check if the cluster is large enough
        if length(Iplace)<targetsize 
            %If not, extend by adding students from the smallest cluster
            Iplace = [Iplace Itoplace(Is(1:targetsize-length(Iplace)))];
        elseif length(Iplace)>targetsize 
            %If not, remove students from this cluster
            Iplace = Iplace(1:targetsize);
        end
        
% % % % %         if length(Iplace)~=12 && length(Iplace)~=13, keyboard; end
        
        %Insert cluster into child
        xoverKids(i,Iplace) = nextclust;

        tmpkids = xoverKids(i,:);
        tmpkids(tmpkids==0) = [];
        tmp1 = histc(tmpkids,1:nextclust);
        if ~all(tmp1==12 | tmp1==11), keyboard; end
        
        nextclust = nextclust + 1;
        
        %Update the clustersize histogram
        Isize = thisClustSizes==length(Iplace);
        thisClustHist(Isize) = thisClustHist(Isize) - 1;
        
        %Remove entries
        [Itoplace,Ia] = setdiff(Itoplace,Iplace);
        Ctoplace = Ctoplace(Ia);
    end
    
% % % % % % %     tmp1 = histc(xoverKids(i,:),1:max(xoverKids(i,:)));
% % % % % % %     if ~all(tmp1==12 | tmp1==13), keyboard; end
end

